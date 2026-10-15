<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleForm extends Component
{
    public ?int $roleId = null;
    public string $name = '';
    public array $selectedPermissions = [];

    public function mount(?int $roleId = null): void
    {
        if ($roleId) {
            $this->roleId = $roleId;
            $role = Role::findOrFail($roleId);
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create(['name' => $this->name]);
        }

        $role->syncPermissions(
            Permission::whereIn('id', $this->selectedPermissions)->get()
        );

        session()->flash('success', $this->roleId ? 'Role updated successfully.' : 'Role created successfully.');
        $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.role-form', [
            'permissions' => Permission::all(),
        ])->layout('layouts.admin');
    }
}
