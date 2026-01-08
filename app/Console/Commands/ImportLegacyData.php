<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Representative;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportLegacyData extends Command
{
    protected $signature = 'app:import-legacy-leads';
    protected $description = 'Import legacy leads from text file';

    public function handle()
    {
        $filePath = base_path('olddata/data.txt');
        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $content = file_get_contents($filePath);
        
        // Mapping aliases to standard medicine names
        $medicineMap = [
            'HOO-IMM PLUS B' => 'HOO-IMM PLUS B',
            'HOO-IMM PLUS OD' => 'HOO-IMM PLUS OD',
            'HOO-IMM PLUS (OLD-HIV)' => 'HOO-IMM PLUS OD',
        ];

        // Ensure Medicines Exist
        $this->ensureMedicinesExist(array_unique(array_values($medicineMap)));

        // Sections are separated by "========================="
        $sections = explode('=========================', $content);

        foreach ($sections as $section) {
            $this->processSection(trim($section), $medicineMap);
        }

        $this->info('Import completed successfully.');
    }

    private function ensureMedicinesExist($medicines)
    {
        foreach ($medicines as $name) {
            Medicine::firstOrCreate(
                ['name' => $name],
                [
                    'code' => Str::slug($name),
                    'description' => 'Imported Medicine',
                    'pack_duration_days' => 30, // Defaulting to 30 days
                    'price' => 0,
                    'status' => 'active'
                ]
            );
        }
    }

    private function processSection($text, $medicineMap)
    {
        if (empty($text)) return;

        // Parse Header: "Ghana Patients Data: Assign to samuelpope419@gmail.com"
        $lines = explode("\n", $text);
        $headerLine = trim($lines[0]);
        
        // Extract Country and Email
        // Regex to capture "Country Patients Data" or similar and email
        if (!preg_match('/^(.*?) Patients Data.*Assign to ([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $headerLine, $matches)) {
            // Try simpler match if first fails
            if (!preg_match('/Assign to ([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $headerLine, $matches)) {
                $this->warn("Skipping section, could not parse header: " . $headerLine);
                return;
            }
            $country = 'Unknown';
        } else {
            $country = trim($matches[1]);
        }
        $email = trim($matches[2] ?? $matches[1]);

        $this->info("Processing: $country -> $email");

        // Create or Find Representative
        $rep = $this->getRepresentative($email, $country);

        // Process Table Rows
        // Format: | Name | Phone | Medicine Name | Date |
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, 'Ghana') || str_starts_with($line, 'Nigeria') || str_starts_with($line, 'Liberia') || str_starts_with($line, 'Tanzania') || str_starts_with($line, '---') || str_starts_with($line, '| Name')) {
                continue;
            }

            if (!str_starts_with($line, '|')) continue;

            $cols = array_map('trim', explode('|', $line));
            // Index 0 is empty (before first |), 1 is Name, 2 is Phone, 3 is Medicine, 4 is Date
            if (count($cols) < 5) continue;

            $name = $cols[1];
            $phone = $cols[2];
            $medNameRaw = $cols[3];
            $dateRaw = $cols[4];

            // Normalize Medicine Name
            $medName = $medicineMap[$medNameRaw] ?? $medNameRaw;
            $medicine = Medicine::where('name', $medName)->first();

            if (!$medicine) {
                $this->warn("Medicine not found for: $medNameRaw");
                continue;
            }

            // Create Patient
            $fullPhone = $phone; // Assuming file has code attached like +233...
            
            $patient = Patient::firstOrCreate(
                ['phone' => $fullPhone],
                [
                    'representative_id' => $rep->id,
                    'name' => $name,
                    'country' => $country,
                    'notes' => 'Imported from legacy data',
                ]
            );

            // Create Order
            // Date format in file seems to be M/D/YYYY (e.g., 9/23/2025)
            // Carbon parse usually handles standard dates well
            try {
                $startDate = Carbon::parse($dateRaw);
            } catch (\Exception $e) {
                $this->error("Invalid date: $dateRaw for $name");
                continue;
            }

            // Avoid Duplicate Orders (same patient, same medicine, same date)
            $exists = Order::where('patient_id', $patient->id)
                ->where('medicine_id', $medicine->id)
                ->where('treatment_start_date', $startDate->format('Y-m-d'))
                ->exists();

            if (!$exists) {
                Order::create([
                    'patient_id' => $patient->id,
                    'medicine_id' => $medicine->id,
                    'representative_id' => $rep->id,
                    'packs_ordered' => 1, // Default to 1
                    'treatment_start_date' => $startDate,
                    'expected_renewal_date' => $startDate->copy()->addDays($medicine->pack_duration_days),
                    'status' => 'active',
                    'notes' => 'Legacy Import',
                ]);
            }
        }
    }

    private function getRepresentative($email, $country)
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'password' => Hash::make('password'),
                'role' => 'representative',
            ]
        );

        return Representative::firstOrCreate(
            ['user_id' => $user->id],
            [
                'country' => $country !== 'Unknown' ? $country : 'Global',
                'region' => 'Imported',
                'phone' => '0000000000',
                'status' => 'active'
            ]
        );
    }
}
