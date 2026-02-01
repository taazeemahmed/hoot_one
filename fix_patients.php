<?php
use App\Models\User;
use App\Models\Patient;

$email = 'kabirkt2003@gmail.com';
$user = User::where('email', $email)->first();

if ($user && $user->representative) {
    $repId = $user->representative->id;
    
    // Update 'new' patients to NULL (Standard Patient)
    $updated = Patient::where('representative_id', $repId)
        ->where('lead_status', 'new')
        ->update(['lead_status' => null]);
        
    echo "Successfully transferred $updated records from 'Lead' list to 'My Patients' list.\n";
} else {
    echo "Representative not found.\n";
}
