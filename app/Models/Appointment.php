<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_name',
        'user_id',
        'address',
        'phone_number',
        'email',
        'session_id',
        'treatment_status',
        'prescription',
        'dr_name',
    ];

    public function appsession()
    {
        return $this->belongsTo(Appsession::class, 'session_id');
    }

    
}
