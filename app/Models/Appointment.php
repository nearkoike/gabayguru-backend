<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mentor() {
        return $this->belongsTo(User::class, 'mentor_id');
    }
    
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
