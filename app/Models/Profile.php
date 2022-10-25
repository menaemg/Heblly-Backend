<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public $guarded = [];

    public function getAvatarUrlAttribute($value)
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('storage/avatars/default.png');
    }
    public function getCoverUrlAttribute($value)
    {
        return $this->cover ? asset('storage/covers/' . $this->cover) : asset('storage/covers/default.png');
    }
}
