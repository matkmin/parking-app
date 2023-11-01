<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdmin();
        $this->createUser();
    }

    public function createAdmin()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'aminshamsuddin98@gmail.com',
            'password' => bcrypt('Amin1234567_'),
        ])->roles()->sync(Role::where('name', RoleName::ADMIN->value)->first());
    }

    public function createUser()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'muqri@seda.gov.my',
            'password' => bcrypt('Amin1234567_'),
        ]);
        $user->roles()->sync(Role::where('name', RoleName::USER->value)->first());
    }
}
