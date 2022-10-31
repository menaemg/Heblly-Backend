<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishboard extends Model
{
    use HasFactory;
    protected $table = 'wishboard';
    protected $fillable = [
        'user_id',
        'post_id',
        'from_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function gratitude()
    {
        return $this->belongsTo(Gratitude::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
