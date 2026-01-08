<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Representative;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hootone.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Representative
        $repUser = User::create([
            'name' => 'John Representative',
            'email' => 'rep@hootone.com',
            'password' => Hash::make('password'),
            'role' => 'representative',
        ]);

        $rep = Representative::create([
            'user_id' => $repUser->id,
            'country' => 'India',
            'region' => 'North',
            'phone' => '+91 9876543210',
            'status' => 'active',
        ]);

        // Medicines
        $medicines = [
            ['name' => 'HOO-IMM PLUS B', 'code' => 'HIP-B', 'pack_duration_days' => 30],
            ['name' => 'HOO-IMM PLUS OD', 'code' => 'HIP-OD', 'pack_duration_days' => 30],
        ];

        foreach ($medicines as $med) {
            \App\Models\Medicine::create($med);
        }
    }
}
