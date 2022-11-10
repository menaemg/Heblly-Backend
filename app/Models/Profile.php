<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? Storage::disk('s3')->url($this->avatar) : null;
    }
    public function getCoverUrlAttribute()
    {
        return $this->cover ? Storage::disk('s3')->url($this->cover) : null;
    }

    public function getFullNameAttribute($value)
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return $this->user->username;
        }
    }

    public function getGenderAttribute($value)
    {
        if ($value === 1){
            return 'male';
        }

        if ($value === 0){
            return 'female';
        }
        return null;
    }
}
