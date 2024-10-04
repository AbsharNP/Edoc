<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Appsession;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function getSessionsByDoctorId($doctorId)
    {
        $validator = Validator::make(['doctorId' => $doctorId], [
            'doctorId' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid doctor ID.'], 400);
        }

        // Fetch doctor information
        $doctor = Doctor::find($doctorId);
        
        // Fetch sessions
        $sessions = Appsession::where('doctor_id', $doctorId)->get();

        if ($sessions->isEmpty()) {
            return response()->json(['doctor' => $doctor, 'sessions' => [], 'message' => 'No sessions found for this doctor.'], 404);
        }

        // Format session details before returning
        return response()->json([
            'doctor' => $doctor,
            'sessions' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_date' => $session->session_date ? $session->session_date->format('Y-m-d') : null,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'no_of_sessions' => $session->no_of_sessions,
                ];
            }),
        ]);
    }


    public function appStore(AppointmentRequest $request)
    {
        $validatedData = $request->validated();
    
        $appointment = Appointment::create($validatedData);

        return response()->json(['message' => 'Appointment booked successfully', 'appointment' => $appointment], 201);
    }

}
