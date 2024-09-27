<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function depStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        // Create a new department
        $department = Department::create([
            'name' => $request->name,
        ]);

        return response()->json($department, 201); // Return the created department
    }

    public function depIndex()
    {
        // Fetch all departments from the database and return as JSON
        return response()->json(Department::all());
    }

    public function depDestroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete(); // Soft delete
        return response()->json(null, 204); // Return no content on success
    }


    public function docStore(DoctorRequest $request)
    {
        // Validation will be handled automatically by StoreDoctorRequest
        
        $doc='doctor';
        // Create a new doctor
        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'doc_id' => $request->doc_id,
            'phone_number' => $request->phone_number,
            'department_id' => $request->department,
            'password' => bcrypt($request->password), // Hash the password
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $doc,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        return response()->json([
            'message' => 'Doctor registered successfully',
            'doctor' => $doctor,
            'user' => $user, // Return user data if needed
        ], 201);   
    
    }

    public function docIndex()
    {
        
        $doctors = Doctor::with('department')->get();

        return response()->json($doctors);
    }

    public function docDestroy(int $id)
    {
        // Find the doctor by ID
        $doctor = Doctor::find($id);

        // Check if the doctor exists
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found.'], 404);
        }

        // Soft delete the doctor
        $doctor->delete();

        // Return a success response
        return response()->json(['message' => 'Doctor soft-deleted successfully.']);
    }

    
}
