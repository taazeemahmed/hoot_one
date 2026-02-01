<?php
use App\Models\User;
use App\Models\Patient;

$email = 'kabirkt2003@gmail.com';
$user = User::where('email', $email)->first();

if ($user && $user->representative) {
    $repId = $user->representative->id;
    
    // Update 'new' patients to 'converted' (Standard Patient Status seems to be 'converted' or custom)
    // Since 'lead_status' is not nullable, use 'converted'.
    $updated = Patient::where('representative_id', $repId)
        ->where('lead_status', 'new')
        ->update(['lead_status' => 'converted']);
        
    echo "Successfully transferred $updated records from 'Lead' list to 'My Patients' list (Status: converted).\n";
} else {
    echo "Representative not found.\n";
}
