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

    public function user_bio()
    {
        return $this->hasOne(UserBio::class);
    }

    public function transactions()
    {
        return $this->hasMany(UserTransaction::class);
    }

    public function student_appointments()
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    public function mentor_appointments()
    {
        return $this->hasMany(Appointment::class, 'mentor_id');
    }

    public function sender_receipts()
    {
        return $this->hasMany(Receipt::class, 'sender_id');
    }

    public function receiver_receipts()
    {
        return $this->hasMany(Receipt::class, 'receiver_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function student_tickets()
    {
        return $this->hasMany(Ticket::class, 'student_id');
    }

    public function support_tickets()
    {
        return $this->hasMany(Ticket::class, 'support_id');
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function payment_details()
    {
        return $this->hasOne(UserPaymentDetail::class);
    }

    public function getClassesAttribute()
    {
        $type = $this->role == 2 ? "mentor_id" : "student_id";
        $appointmentIds = Appointment::where($type, $this->id)->whereIn('status', ['DONE', 'FAILED'])->pluck('id');
        return Classes::whereIn('appointment_id', $appointmentIds)->get();
    }

    public function getReviewsAttribute()
    {
        $type = $this->role == 2 ? "mentor_id" : "student_id";
        $appointmentIds = Appointment::where($type, $this->id)->pluck('id');
        $classIds = Classes::whereIn('appointment_id', $appointmentIds)->pluck('id');

        return Review::whereIn('class_id', $classIds)->get();
    }
}
