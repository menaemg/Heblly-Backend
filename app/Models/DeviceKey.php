<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_key',
        'auth_key'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
