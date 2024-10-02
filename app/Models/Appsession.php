<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appsession extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'session_date', // Use the correct column name
        'start_time',
        'end_time',
        'no_of_sessions',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    protected $casts = [
        'session_date' => 'date',
    ];
}