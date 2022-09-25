<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'cpanel']);
        $role->givePermissionTo($permission);
        $user = User::find(1);
        $user->assignRole('admin');

        $role2 = Role::create(['name' => 'mod']);
        $permission2 = Permission::create(['name' => 'mod']);
        $role2->givePermissionTo($permission2);
        $user2 = User::find(1);
        $user2->assignRole('mod');
    }
}
