<?php

namespace App\Http\Controllers;

use App\Models\Appsession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function getSessionsByDoctorId($doctorId)
    {
        // Validate the doctor ID if necessary
        $validator = Validator::make(['doctorId' => $doctorId], [
            'doctorId' => 'required|exists:doctors,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid doctor ID.'], 400);
        }
    
        // Fetch sessions related to the specific doctor
        $sessions = Appsession::where('doctor_id', $doctorId)->get(); // Ensure to use the correct model
    
        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No sessions found for this doctor.'], 404);
        }
    
        // Format the date and time before returning
        return response()->json($sessions->map(function ($session) {
            return [
                'id' => $session->id,
                'session_date' => $session->session_date ? $session->session_date->format('Y-m-d') : null, // Check if date is null
                'start_time' => $session->start_time, // Directly use the string
                'end_time' => $session->end_time, // Directly use the string
                'no_of_sessions' => $session->no_of_sessions,
            ];
        }));
}
}