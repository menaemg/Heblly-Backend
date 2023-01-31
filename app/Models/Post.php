<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use App\Traits\ImageFile;
use App\Traits\DiffForHumans;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Overtrue\LaravelLike\Traits\Likeable;
use BeyondCode\Comments\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, HasTags, HasSlug, ImageFile, DiffForHumans, HasComments, Likeable;

    /**
     * Get the options for generating the slug.
     */

    public $casts = [
        'images' => 'array',
        'access_list' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'for_id',
        'from_id'
    ];

    protected $fillable = [
        'title',
        'body',
        'slug',
        'main_image',
        'images',
        'status',
        'action',
        'location',
        'privacy',
        'user_id',
        'access_list',
        'tags',
        'from_id',
        'for_id',
        'type',
        'created_at',
        'updated_at',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setMainImageAttribute($value)
    {
        if (is_file($value)) {
            $image = $this->uploadImage($value, 'posts');
            if ($image) {
                if ($this->main_image) {
                    $this->deleteImage($this->getRawOriginal('main_image'));
                }
                $this->attributes['main_image'] = $image;
            }
        }
    }

    public function getMainImageAttribute($value)
    {
        return $value ? Storage::disk('s3')->url($value) : null;
    }

    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            foreach ($value as $image) {
                if (is_file($image)) {
                    $images[] = $this->uploadImage($image, 'posts');
                }
            }
            $this->attributes['images'] = json_encode($images);
        }
    }

    public function getImagesAttribute($value)
    {
        $value = json_decode($value);
        if ($value && is_array($value) && !empty($value)) {
            foreach ($value as $image) {
                if ($image) {
                    $images[] = Storage::disk('s3')->url($image);
                }
            }
            return $images ?? [];
        }
    }

    public function reserved()
    {
        return $this->hasOne(Reserve::class);
    }

    public function from_friend()
    {
        return $this->belongsTo(User::class, 'from_id')->select('id', 'username');
    }

    public function for_friend()
    {
        return $this->belongsTo(User::class, 'for_id')->select('id', 'username');
    }

    public function getIsWishListAttribute()
    {
        $authUser = auth('sanctum')->check() ? auth('sanctum')->user() : null;

        if ($authUser) {
            $wishList = $authUser->wishList()->where('post_id', $this->id)->first();

            return $wishList ? true : false;
        }

        return false;
    }

    public function report()
    {
        return $this->morphOne(Report::class, 'reportable');
    }

    public function scopeFilter(Builder $builder, $request)
    {
        $filter = $request->query('filter');
        if ($filter) {
            foreach ($filter as $field => $query) {
                if (in_array($field, $this->allowedFilters) && $query) {
                    $field = strpos($field, '.') ? explode('.', $field) : $field;
                    if (is_array($field)) {
                        $builder->whereHas($field[0], function ($q) use ($query, $field) {
                            $q->where($field[1], 'sounds like', "%$query%");
                        });
                    } else {
                        $builder->where($field, 'like', "%$query%")->orWhere($field, 'sounds like', "%$query%");
                    }
                }
            }
        }
    }
}
