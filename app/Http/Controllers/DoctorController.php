<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSessionRequest;
use App\Models\Appointment;
use App\Models\Appsession;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    

    

    // Submit prescription
    public function submitPrescription(Request $request)
    {
        // Validate the request data
        $request->validate([
            'appointmentId' => 'required|exists:appointments,id', // Ensure the appointment exists
            'prescription' => 'required|string',
            'treatment_status' => 'required|integer',
        ]);

        // Find the appointment by ID
        $appointment = Appointment::find($request->appointmentId);

        // Update the appointment with the prescription and treatment status
        $appointment->prescription = $request->prescription;
        $appointment->dr_name = $request->dr_name;
        $appointment->treatment_status = $request->treatment_status;
        $appointment->save(); // Save the changes to the database

        return response()->json(['message' => 'Prescription submitted successfully'], 200);
    }

    public function show($id)
    {
        // Fetch the appointment by ID, along with the related session data
        $appointment = Appointment::with('appsession')->findOrFail($id);

        // Format the response data
        $data = [
            'patient_name' => $appointment->patient_name,
            'phone_number' => $appointment->phone_number,
            'address' => $appointment->address,
            'email' => $appointment->email,
            'session_date' => optional($appointment->appsession)->session_date, // Assuming 'date' is the column name in the appsession table
            'treatment_status' => $appointment->treatment_status,
            'prescription' => $appointment->prescription,
        ];

        return response()->json($data);
    }

   

}
