<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_name',
        'address',
        'phone_number',
        'email',
        'session_id',
        'treatment_completed',
        'prescription',
    ];
}
