<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        // You can add logic here to authorize users based on roles, etc.
        return true; // Allow all users to make requests for now
    }

    public function rules()
    {
        return [
            'patient_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'session_id' => 'required|exists:appsessions,id', // Ensure the session ID exists
            'treatment_completed' => 'nullable|boolean',
            'prescription' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'patient_name.required' => 'Patient name is required.',
            'address.required' => 'Address is required.',
            'phone_number.required' => 'Phone number is required.',
            'session_id.required' => 'Session ID is required.',
            'session_id.exists' => 'The selected session is invalid.',
            'email.email' => 'Email must be a valid email address.',
        ];
    }
}
