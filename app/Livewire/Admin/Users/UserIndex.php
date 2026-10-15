<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public string $filterRole = '';
    public string $filterVerified = '';

    // Sorting
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // Per page
    public int $perPage = 10;

    // Bulk selection
    public array $selected = [];
    public bool $selectAll = false;
    public bool $selectPage = false;

    // Reset pagination on filter change
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingFilterVerified(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    // Sort
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Select all on current page
    public function updatedSelectPage(bool $value): void
    {
        if ($value) {
            $this->selected = $this->getUsersQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Select all across all pages
    public function selectAllUsers(): void
    {
        $this->selectAll = true;
        $this->selected = $this->getUsersQuery(paginate: false)
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

    // Delete single user
    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        if ($user->hasRole('super-admin')) {
            session()->flash('error', 'Cannot delete a super-admin user.');
            return;
        }

        $user->delete();
        $this->resetSelection();
        session()->flash('success', 'User deleted successfully.');
    }

    // Bulk delete
    public function bulkDelete(): void
    {
        $users = User::whereIn('id', $this->selected)->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($users as $user) {
            if ($user->id === auth()->id() || $user->hasRole('super-admin')) {
                $skipped++;
                continue;
            }
            $user->delete();
            $deleted++;
        }

        $this->resetSelection();

        if ($deleted === 0) {
            session()->flash('error', "{$skipped} user(s) could not be deleted (super-admin or your own account).");
        } else {
            $message = "{$deleted} user(s) deleted.";
            if ($skipped > 0) {
                $message .= " {$skipped} skipped (super-admin or self).";
            }
            session()->flash('success', $message);
        }
    }

    // Build query
    private function getUsersQuery(bool $paginate = true)
    {
        $query = User::with('roles')
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterRole, function ($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', $this->filterRole));
            })
            ->when($this->filterVerified !== '', function ($q) {
                if ($this->filterVerified === 'verified') {
                    $q->whereNotNull('email_verified_at');
                } elseif ($this->filterVerified === 'unverified') {
                    $q->whereNull('email_verified_at');
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return $paginate ? $query->paginate($this->perPage) : $query->get();
    }

    public function render()
    {
        return view('livewire.admin.users.user-index', [
            'users' => $this->getUsersQuery(),
            'roles' => Role::all(),
            'selectedCount' => count($this->selected),
        ])->layout('layouts.admin');
    }
}
