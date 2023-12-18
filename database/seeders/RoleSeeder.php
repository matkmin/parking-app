<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createUserRole();
    }

    protected function createRole(RoleName $role, Collection $permissions): void
    {
        $newRole = Role::create(['name' => $role->value]);
        $newRole->permissions()->sync($permissions);
    }

    protected function createAdminRole(): void
    {
        $permission = Permission::query()
            ->where('name', 'like', 'admin.%')
            ->orWhere('name', 'like', 'user.%')
            ->pluck('id');

        $this->createRole(RoleName::ADMIN, $permission);
    }
    protected function createUserRole(): void
    {
        $permission = Permission::whereIn('name', [
            'user.viewany',
        ])->get();

        $this->createRole(RoleName::USER, $permission);
    }
}
