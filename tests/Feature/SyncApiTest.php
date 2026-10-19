<?php

namespace Tests\Feature;

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_status_requires_valid_api_key(): void
    {
        // Tanpa API Key
        $response = $this->getJson('/api/v1/sync/status');
        $response->assertStatus(401);

        // Dengan API Key yang salah
        $response = $this->withHeader('X-API-KEY', 'wrong-key')->getJson('/api/v1/sync/status');
        $response->assertStatus(401);

        // Dengan API Key yang benar
        $response = $this->withHeader('X-API-KEY', 'sr-sync-secret-key-2026')->getJson('/api/v1/sync/status');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'stats' => ['total_participants', 'finished', 'dns', 'dnf'],
            ]);
    }

    public function test_push_participants_sync_upserts_data(): void
    {
        $payload = [
            'batch_id' => 'batch-test-001',
            'event_name' => 'Marathon Test 2026',
            'participants' => [
                [
                    'no_bib' => '7796',
                    'chip_code' => 'CHIP-7796',
                    'nama_lengkap' => 'Budi Finisher',
                    'gender' => 'Male',
                    'kategori' => '10K',
                    'sub_kategori' => 'Open',
                    'status' => 'finished',
                    'pace' => '04:30',
                    'gun_time' => '00:45:30',
                    'chip_time' => '00:45:00',
                ],
                [
                    'no_bib' => '7797',
                    'chip_code' => 'CHIP-7797',
                    'nama_lengkap' => 'Siti Runner',
                    'gender' => 'Female',
                    'kategori' => '5K',
                    'status' => 'dns',
                ]
            ]
        ];

        $response = $this->withHeader('X-API-KEY', 'sr-sync-secret-key-2026')
            ->postJson('/api/v1/sync/participants', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'synced_count' => 2,
            ]);

        $this->assertDatabaseHas('paticipants', [
            'no_bib' => '7796',
            'nama_lengkap' => 'Budi Finisher',
            'status' => 'finished',
            'chip_time' => '00:45:00',
        ]);

        $this->assertDatabaseHas('sync_logs', [
            'batch_id' => 'batch-test-001',
            'status' => 'success',
        ]);
    }

    public function test_public_participant_search_and_show(): void
    {
        Participant::create([
            'no_bib' => '8899',
            'chip_code' => 'CP8899',
            'nama_lengkap' => 'Agus Pelari',
            'kategori' => '10K',
            'status' => 'finished',
            'chip_time' => '00:48:12',
        ]);

        $response = $this->getJson('/api/v1/participants/8899');
        $response->assertStatus(200)
            ->assertJsonPath('data.nama_lengkap', 'Agus Pelari');

        $webResponse = $this->get('/?q=Agus');
        $webResponse->assertStatus(200)
            ->assertSee('Agus Pelari')
            ->assertSee('00:48:12');
    }
}
