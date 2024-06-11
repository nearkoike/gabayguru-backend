<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'classes';

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }
    
    public function review() {
        return $this->hasOne(Review::class);
    }
}
