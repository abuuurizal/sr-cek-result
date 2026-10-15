<?php

namespace App\Livewire\Admin\Events;

use App\Models\Participant;
use Livewire\Component;
use Livewire\WithPagination;

class EventIndex extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $filterStatus = '';
    public string $filterGender = '';
    public string $filterKategori = '';

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Per Page
    public int $perPage = 25;

    // Bulk selection
    public array $selected = [];
    public bool $selectAll = false;
    public bool $selectPage = false;

    // Reset pagination and selection on filter update
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingFilterGender(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectPage(bool $value): void
    {
        if ($value) {
            $this->selected = $this->getParticipantsQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function selectAllParticipants(): void
    {
        $this->selectAll = true;
        $this->selected = $this->getParticipantsQuery(paginate: false)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    private function resetSelection(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function deleteParticipant(int $id): void
    {
        Participant::findOrFail($id)->delete();
        $this->resetSelection();
        session()->flash('success', __('Participant deleted successfully.'));
    }

    public function bulkDelete(): void
    {
        $deleted = Participant::whereIn('id', $this->selected)->delete();
        $this->resetSelection();

        if ($deleted === 0) {
            session()->flash('error', __('No participants were deleted.'));
        } else {
            session()->flash('success', __(':count participant(s) deleted.', ['count' => $deleted]));
        }
    }

    public function bulkSetStatus(string $status): void
    {
        if (!in_array($status, ['finished', 'dns', 'dnf'])) {
            return;
        }

        $updated = Participant::whereIn('id', $this->selected)->update(['status' => $status]);
        $this->resetSelection();

        session()->flash('success', __(':count participant(s) updated to :status.', [
            'count' => $updated,
            'status' => strtoupper($status),
        ]));
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterGender', 'filterKategori']);
        $this->resetPage();
        $this->resetSelection();
    }

    private function getParticipantsQuery(bool $paginate = true)
    {
        $query = Participant::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_lengkap', 'like', "%{$this->search}%")
                        ->orWhere('no_bib', 'like', "%{$this->search}%")
                        ->orWhere('chip_code', 'like', "%{$this->search}%")
                        ->orWhere('kategori', 'like', "%{$this->search}%")
                        ->orWhere('sub_kategori', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterStatus !== '', fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterGender !== '', fn($q) => $q->where('gender', $this->filterGender))
            ->when($this->filterKategori !== '', fn($q) => $q->where('kategori', $this->filterKategori))
            ->orderBy($this->sortField, $this->sortDirection);

        return $paginate ? $query->paginate($this->perPage) : $query->get();
    }

    public function render()
    {
        $stats = [
            'total' => Participant::count(),
            'finished' => Participant::where('status', 'finished')->count(),
            'dns' => Participant::where('status', 'dns')->count(),
            'dnf' => Participant::where('status', 'dnf')->count(),
        ];

        return view('livewire.admin.events.event-index', [
            'participants' => $this->getParticipantsQuery(),
            'stats' => $stats,
            'categories' => Participant::whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori')->sort()->values(),
            'genders' => Participant::whereNotNull('gender')->where('gender', '!=', '')->distinct()->pluck('gender')->sort()->values(),
            'selectedCount' => count($this->selected),
        ])->layout('layouts.admin');
    }
}
