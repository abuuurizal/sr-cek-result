<?php

namespace Tests\Feature;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ParticipantUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_participant_and_refreshes_timestamps_for_delta_sync(): void
    {
        // Buat peserta dengan status finished di masa lalu
        $past = Carbon::now()->subHours(2);
        $participant = Participant::create([
            'no_bib' => '1001',
            'nama_lengkap' => 'Nama Lama',
            'gender' => 'M',
            'status' => 'finished',
            'chip_time' => '00:50:00',
            'gun_time' => '00:51:00',
            'pace' => '05:00',
        ]);
        DB::table('paticipants')->where('id', $participant->id)->update([
            'created_at' => $past,
            'updated_at' => $past,
            'synced_at' => $past,
        ]);

        $sinceCheckpoint = Carbon::now()->subHour();

        // Sebelum diedit, delta sejak checkpoint tidak menyertakan peserta ini
        $deltaBefore = $this->getJson('/api/v1/participants/delta?since='.urlencode($sinceCheckpoint->toIso8601String()));
        $deltaBefore->assertStatus(200);
        $this->assertCount(0, $deltaBefore->json('data'));

        // Lakukan edit melalui endpoint web
        $payload = [
            'nama_lengkap' => 'Nama Baru Diedit',
            'gender' => 'M',
            'status' => 'finished',
            'chip_time' => '00:48:30',
            'gun_time' => '00:49:10',
            'pace' => '04:51',
        ];

        $response = $this->putJson("/participants/{$participant->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data peserta berhasil diperbarui.',
            ]);

        $fresh = $participant->fresh();
        $this->assertSame('Nama Baru Diedit', $fresh->nama_lengkap);
        $this->assertSame('00:48:30', $fresh->chip_time);
        $this->assertNotNull($fresh->synced_at);
        $this->assertTrue($fresh->updated_at->isAfter($sinceCheckpoint));

        // Setelah diedit, delta sync sr-tarik-data mengambil data ini kembali
        $deltaAfter = $this->getJson('/api/v1/participants/delta?since='.urlencode($sinceCheckpoint->toIso8601String()));
        $deltaAfter->assertStatus(200);
        $this->assertCount(1, $deltaAfter->json('data'));
        $this->assertSame('1001', $deltaAfter->json('data.0.no_bib'));
        $this->assertSame('Nama Baru Diedit', $deltaAfter->json('data.0.nama_lengkap'));
    }
}
