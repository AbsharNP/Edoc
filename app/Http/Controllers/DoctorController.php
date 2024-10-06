<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSessionRequest;
use App\Models\Appointment;
use App\Models\Appsession;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function getDoctorByEmail($email)
    {
        $doctor = Doctor::where('email', $email)->first();
        
        if ($doctor) {
            return response()->json(['id' => $doctor->id], 200);
        } else {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }

    public function Sessionstore(AppSessionRequest $request)
    {
        $session = Appsession::create([
            'doctor_id' => $request->doctor_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'no_of_sessions' => $request->no_of_sessions,
        ]);

        return response()->json(['message' => 'Session created successfully', 'session' => $session], 201);
    }

    public function getUpcomingSessions($doctorId)
    {
        $today = Carbon::today();

        $sessions = Appsession::where('doctor_id', $doctorId)
                    ->whereDate('s_date', '>=', $today)  // Fetch sessions from today and onwards
                    ->orderBy('s_date', 'asc')  // Sort by date in ascending order
                    ->limit(7)  // Limit to 7 rows
                    ->get();

        return response()->json($sessions);
    }

    public function getSessions($doctorId)
    {
        
        $sessions = Appsession::where('doctor_id', $doctorId)
                    ->where('session_date', '>=', now()) 
                    ->orderBy('session_date', 'asc')     
                    ->get();

        return response()->json($sessions);
    }


    public function getAppointmentsByDoctorId($doctorId)
    {
        // Fetch all appointments for a specific doctor along with the session date
        $appointments = Appointment::select('appointments.*', 'appsessions.session_date as session_date') // Include session date
            ->join('appsessions', 'appointments.session_id', '=', 'appsessions.id')
            ->where('appsessions.doctor_id', $doctorId)
            ->get();
    
        return response()->json($appointments);
    }
    
    // Method to fetch today's appointments
    public function getTodaysAppointmentsByDoctorId($doctorId)
    {
        $today = now()->format('Y-m-d');
    
        // Fetch today's appointments along with the session date
        $appointments = Appointment::select('appointments.*', 'appsessions.session_date as session_date') // Include session date
            ->join('appsessions', 'appointments.session_id', '=', 'appsessions.id')
            ->where('appsessions.doctor_id', $doctorId)
            ->whereDate('appsessions.session_date', $today) 
            ->get();
    
        return response()->json($appointments);
    } 
    
    public function getCompletedTreatments($doctorId, $sessionId)
{
    // Fetch completed treatments for the doctor in a specific session
    $appointments = Appointment::select('appointments.*', 'appsessions.session_date as session_date')
        ->join('appsessions', 'appointments.session_id', '=', 'appsessions.id')
        ->where('appsessions.doctor_id', $doctorId)
        ->where('appointments.session_id', $sessionId)
        ->where('appointments.treatment_completed', '1') // Assuming this field exists
        ->get();

    return response()->json($appointments);
}
    
}
