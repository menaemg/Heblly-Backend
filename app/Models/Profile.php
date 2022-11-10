<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'bio',
        'gender',
        'phone',
        'avatar',
        'cover',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'local',
        'privacy',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute($value)
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('storage/avatars/default.png');
    }
    public function getCoverUrlAttribute($value)
    {
        return $this->cover ? asset('storage/' . $this->cover) : asset('storage/covers/default.png');
    }

    public function getFullNameAttribute($value)
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return $this->user->username;
        }
    }
}
