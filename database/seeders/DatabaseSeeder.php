<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Super Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'super_admin']);
        User::create(['name' => 'Pastor Emeka Okafor', 'email' => 'emeka@example.com', 'password' => Hash::make('password'), 'role' => 'content_manager']);
        User::create(['name' => 'Segun Ali', 'email' => 'segun@example.com', 'password' => Hash::make('password'), 'role' => 'media_manager']);
        User::create(['name' => 'Kemi Salako', 'email' => 'kemi@example.com', 'password' => Hash::make('password'), 'role' => 'moderator', 'groups' => ['Prayer Ministry']]);

        collect([
            ['Ngozi Adeyemi', ["Women's Fellowship"]],
            ['David Okonkwo', ["Men's Fellowship"]],
            ['Ifeoma Eze', ["Children's Church"]],
            ['Chidi Mbanefo', []],
            ['Tobi Johnson', ['Youth']],
        ])->each(fn ($m) => User::create([
            'name' => $m[0],
            'email' => strtolower(explode(' ', $m[0])[0]) . '@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'groups' => $m[1],
        ]));
    }
}
