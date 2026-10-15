<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public function deleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        // Prevent deleting super-admin role
        if ($role->name === 'super-admin') {
            session()->flash('error', 'Cannot delete super-admin role.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.roles.role-index', [
            'roles' => Role::with('permissions')->get(),
        ])->layout('layouts.admin');
    }
}
