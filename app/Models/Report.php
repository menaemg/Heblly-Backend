<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'description',
        'user_id'
    ];

    public function reportable()
    {
        return $this->morphTo();
    }
}
