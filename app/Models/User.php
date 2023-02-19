<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\DeviceKey;
use App\Scopes\NotBlockedScope;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Overtrue\LaravelLike\Traits\Liker;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Traits\Follower;
use Overtrue\LaravelFollow\Traits\Followable;
use BeyondCode\Comments\Contracts\Commentator;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Commentator
{
    use HasApiTokens, HasFactory, Notifiable, Follower, Followable, Liker;

        /**
     * Check if a comment for a specific model needs to be approved.
     * @param mixed $model
     * @return bool
     */
    public function needsCommentApproval($model): bool
    {
        return false;
    }

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

    public function getAvatarAttribute()
    {
        return $this->profile ? $this->profile_url : null;
    }

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

    public function getFriendsIdsAttribute()
    {
        $followings = $this->approvedFollowings->load('followable:id,username')->pluck('followable.id');

        $followers = $this->approvedFollowers->pluck('id');

        $friends = $followers->merge($followings);

        return $friends->toArray();
    }

    public function blocklist()
    {
        return $this->hasMany(Block::class);
    }

    public function blockedBy()
    {
        return $this->hasMany(Block::class, 'block_user_id');
    }

    // public function scopeNotBlocked($query)
    // {
    //     return $query->whereDoesntHave('blocklist', function ($query) {
    //         $query->where('block_user_id', auth('sanctum')->user()->id);
    //     })->whereDoesntHave('blockedBy', function ($query) {
    //         $query->where('user_id', auth('sanctum')->user()->id);
    //     });
    // }

    public function getBlockIdsAttribute()
    {
        return $this->blocklist()->where('type', 'all')->pluck('block_user_id')->unique('block_user_id')
                            ->concat($this->blockedBy()->where('type', 'all')->pluck('user_id')->unique('block_user_id'));
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

    public function reserves()
    {
        return $this->hasMany(Reserve::class);
    }

    public function deviceKeys()
    {
        return $this->hasMany(DeviceKey::class);
    }


    public function helps()
    {
        return $this->hasMany(Help::class);
    }

    // public function blockAccess() {
    //     if (in_array($this->id, Auth()->user()->blockIds->toArray())) {
    //         return jsonResponse(false, 'You Can\'t Access This User', 403);
    //     }
    // }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('username', 'like', '%'.$search.'%');
                    // ->orWhere('last_name', 'like', '%'.$search.'%')
                    // ->orWhere('email', 'like', '%'.$search.'%');
            });
        // })->when($filters['role'] ?? null, function ($query, $role) {
        //     $query->whereRole($role);
        // })->when($filters['trashed'] ?? null, function ($query, $trashed) {
        //     if ($trashed === 'with') {
        //         $query->withTrashed();
        //     } elseif ($trashed === 'only') {
        //         $query->onlyTrashed();
        //     }
        });
    }


    protected static function booted()
    {
        parent::boot();

        $authUser = auth('sanctum')->check() ? auth('sanctum')->user() : null;

        static::addGlobalScope(new NotBlockedScope($authUser));
    }


}
