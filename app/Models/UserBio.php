<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBio extends Model
{
    use HasFactory;
    protected $table = "user_bio";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
