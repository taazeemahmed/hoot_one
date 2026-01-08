<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Representative;
use App\Models\Medicine;
use Illuminate\Support\Facades\Hash;

class TestFrontendSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Test Representative
        $email = 'representative_test@hootone.org';
        $user = User::firstWhere('email', $email);

        if (!$user) {
            $user = User::create([
                'name' => 'Frontend Test Rep',
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'representative',
            ]);

            Representative::create([
                'user_id' => $user->id,
                'country' => 'Ghana',
                'country_code' => '+233', // Testing the custom code logic
                'phone' => '+233555555555',
                'status' => 'active',
            ]);
            $this->command->info('Test Representative Created: ' . $email);
        } else {
            // Update existing for correct test state
            $user->update(['password' => Hash::make('password123')]);
            $user->representative()->update([
                'country' => 'Ghana', 
                'country_code' => '+233'
            ]);
            $this->command->info('Test Representative Updated');
        }

        // 2. Ensure a Test Medicine exists
        $medicine = Medicine::firstWhere('name', 'Test Paracetamol');
        if (!$medicine) {
            Medicine::create([
                'name' => 'Test Paracetamol',
                'code' => 'TEST-001',
                'description' => 'For testing purposes',
                'price' => 10.00,
                'pack_duration_days' => 30,
                'status' => 'active',
            ]);
            $this->command->info('Test Medicine Created');
        }
    }
}
