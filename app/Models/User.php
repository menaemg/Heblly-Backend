<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Termwind\Components\Dd;
use App\Scopes\NotBlockedScope;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Notifications\Notifiable;
use Illuminate\Routing\Events\RouteMatched;
use Overtrue\LaravelFollow\Traits\Follower;
use Overtrue\LaravelFollow\Traits\Followable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Follower, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = config('client.url') . '/password/reset/' . Hash::make($token);

        $this->notify(new ResetPasswordNotification($url));
    }

    public function needsToApproveFollowRequests()
    {
        return (bool) true;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function gratitudes()
    {
        return $this->hasMany(Gratitude::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishboard()
    {
        return $this->hasMany(Wishboard::class);
    }

    public function blocklist()
    {
        return $this->hasMany(Block::class);
    }

    public function isBlocked($user)
    {
        $blockedIds = Auth()->user()->blocklist()->pluck('block_user_id')->toArray();

        $userBlocked = $user->blocklist()->pluck('block_user_id')->toArray();

        if (in_array($user->id, $blockedIds) || in_array(Auth()->id(), $userBlocked)) {
            return true;
        }

        return false;
    }

    // protected static function booted()
    // {
    //     parent::boot();

    //     // $authUser = auth('sanctum')->check() ? auth('sanctum')->user() : null;

    //     static::addGlobalScope(new NotBlockedScope());
    // }
}
