<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResetCode extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'code',
        'created_at',
    ];


    public function setCodeAttribute($code)
    {
        $this->attributes['code'] = Hash::make($code);
    }
}
