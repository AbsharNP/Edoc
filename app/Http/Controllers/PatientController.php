<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Appsession;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $doctor = Doctor::find($doctorId);
        
        $sessions = Appsession::where('doctor_id', $doctorId)->get();

        if ($sessions->isEmpty()) {
            return response()->json(['doctor' => $doctor, 'sessions' => [], 'message' => 'No sessions found for this doctor.'], 404);
        }

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
        $userId = $request->user()->id;

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

    public function getDoneAppointments(Request $request)
{
    $userId = $request->query('user_id'); 

    $appointments = Appointment::where('user_id', $userId)
                               ->where('treatment_status', 1) 
                               ->get();

    if ($appointments->isEmpty()) {
        return response()->json(['message' => 'No completed appointments found'], 404);
    }

    return response()->json($appointments, 200);
}

    public function getAppointmentById($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment, 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_pic' => 'nullable|image|max:10048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        if ($request->hasFile('profile_pic')) {
            $filePath = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $filePath;
        }
    
        $user->save();
    
        return response()->json(['message' => 'Profile updated successfully']);
    }
    public function getProfile()
    {
        $user = Auth::user();
        
        return response()->json($user);
    }
}
