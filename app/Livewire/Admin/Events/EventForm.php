<?php

namespace App\Livewire\Admin\Events;

use App\Models\Participant;
use Livewire\Component;

class EventForm extends Component
{
    public ?int $participantId = null;

    public string $chip_code = '';
    public string $no_bib = '';
    public string $nama_lengkap = '';
    public string $gender = '';
    public string $kategori = '';
    public string $sub_kategori = '';
    public string $status = 'dns';
    public string $pace = '';
    public string $gun_time = '';
    public string $chip_time = '';

    public function mount(?int $participantId = null): void
    {
        if ($participantId) {
            $this->participantId = $participantId;
            $participant = Participant::findOrFail($participantId);

            $this->chip_code = $participant->chip_code ?? '';
            $this->no_bib = $participant->no_bib ?? '';
            $this->nama_lengkap = $participant->nama_lengkap ?? '';
            $this->gender = $participant->gender ?? '';
            $this->kategori = $participant->kategori ?? '';
            $this->sub_kategori = $participant->sub_kategori ?? '';
            $this->status = $participant->status ?? 'dns';
            $this->pace = $participant->pace ?? '';
            $this->gun_time = $participant->gun_time ?? '';
            $this->chip_time = $participant->chip_time ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'chip_code' => 'nullable|string|max:255',
            'no_bib' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'gender' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'sub_kategori' => 'nullable|string|max:255',
            'status' => 'required|in:finished,dns,dnf',
            'pace' => 'nullable|string|max:255',
            'gun_time' => 'nullable|string|max:255',
            'chip_time' => 'nullable|string|max:255',
        ]);

        $data = [
            'chip_code' => $this->chip_code ?: null,
            'no_bib' => $this->no_bib ?: null,
            'nama_lengkap' => $this->nama_lengkap,
            'gender' => $this->gender ?: null,
            'kategori' => $this->kategori ?: null,
            'sub_kategori' => $this->sub_kategori ?: null,
            'status' => $this->status,
            'pace' => $this->pace ?: null,
            'gun_time' => $this->gun_time ?: null,
            'chip_time' => $this->chip_time ?: null,
        ];

        if ($this->participantId) {
            Participant::findOrFail($this->participantId)->update($data);
            session()->flash('success', __('Participant updated successfully.'));
        } else {
            Participant::create($data);
            session()->flash('success', __('Participant created successfully.'));
        }

        $this->redirect(route('admin.events.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.events.event-form')
            ->layout('layouts.admin');
    }
}
