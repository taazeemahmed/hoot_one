<?php
use App\Models\User;
use App\Models\Patient;

$email = 'kabirkt2003@gmail.com';
$user = User::where('email', $email)->first();

if (!$user || !$user->representative) {
    echo "User or Representative profile not found for $email\n";
    exit;
}

$repId = $user->representative->id;

echo "Representative ID: $repId\n";

$distribution = Patient::where('representative_id', $repId)
    ->selectRaw('lead_status, count(*) as count')
    ->groupBy('lead_status')
    ->get();

echo "Patient Status Distribution:\n";
foreach ($distribution as $row) {
    $status = $row->lead_status ?? 'NULL (Old Patient)';
    echo "Status [$status]: {$row->count}\n";
}

// Check 'legacy' note patients
$legacy = Patient::where('representative_id', $repId)
    ->where('notes', 'like', '%legacy%')
    ->count();
echo "Patients with 'legacy' in notes: $legacy\n";
