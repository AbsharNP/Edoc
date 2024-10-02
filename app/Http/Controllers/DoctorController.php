<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSessionRequest;
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
}
