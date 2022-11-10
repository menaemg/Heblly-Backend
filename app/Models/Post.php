<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Tags\HasTags;
use App\Traits\ImageFile;
use App\Traits\DiffForHumans;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Termwind\Components\Dd;

class Post extends Model
{
    use HasFactory, HasTags, HasSlug, ImageFile, DiffForHumans;

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

    protected $fillable = [
        'title',
        'body',
        'slug',
        'main_image',
        'images',
        'status',
        'location',
        'privacy',
        'user_id',
        'access_list',
        'tags',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function setMainImageAttribute($value)
    {

        if (is_file($value)) {
            $image = $this->uploadImage($value , 'posts');
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
                $images[] = Storage::disk('s3')->url($image);
            }
            return $images ?? [];
        }
    }
}
