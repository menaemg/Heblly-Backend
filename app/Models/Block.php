<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;
    protected $table = 'blocklist';
    protected $fillable = [
        'user_id',
        'block_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blockUser()
    {
        return $this->belongsTo(User::class, 'block_user_id');
    }
}
