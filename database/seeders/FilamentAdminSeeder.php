<?php

namespace Database\Seeders;

use App\Models\User;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FilamentAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'filament@webshop.com'],
            [
                'name'     => 'Filament Admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}