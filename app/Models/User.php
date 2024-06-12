<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function transactions() {
        return $this->hasMany(UserTransaction::class);
    }

    public function student_appointments() {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    public function mentor_appointments() {
        return $this->hasMany(Appointment::class, 'mentor_id');
    }    

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
    
    public function student_tickets() {
        return $this->hasMany(Ticket::class, 'student_id');
    }

    public function support_tickets() {
        return $this->hasMany(Ticket::class, 'support_id');
    } 
    
    public function penalties() {
        return $this->hasMany(Penalty::class);
    }
}
