<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function support() {
        return $this->belongsTo(User::class, 'support_id');
    }
    
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
