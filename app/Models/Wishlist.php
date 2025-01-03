<?php

namespace App\Models;

use App\Traits\DiffForHumans;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory, DiffForHumans;
    protected $table = 'wishlist';
    protected $fillable = [
        'user_id',
        'post_id',
        'gratitude_id',
        'privacy',
        'access_list',
    ];

    public $casts = [
        'access_list' => 'array',
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
}
