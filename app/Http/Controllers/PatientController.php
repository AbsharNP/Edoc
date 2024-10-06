<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Appsession;
use App\Models\Doctor;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

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
    
        $validatedData['user_id'] = auth()->id();
    
        $appointment = Appointment::create($validatedData);
    
        return response()->json(['message' => 'Appointment booked successfully', 'appointment' => $appointment], 201);
    }

    public function myAppointments(Request $request)
    {
        // Assuming you are using auth to get the logged-in user's ID.
        $userId = $request->user()->id;

        // Fetch appointments along with session and doctor data
        $appointments = Appointment::with(['appsession.doctor'])
            ->where('user_id', $userId) 
            ->get();

        return response()->json($appointments);
    }

    public function destroyApp($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], Response::HTTP_NOT_FOUND);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully'], Response::HTTP_OK);
    }

}
