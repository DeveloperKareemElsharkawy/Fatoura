<?php

namespace App\Models;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory, Likable;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return BelongsToMany
     */
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'likeable_id', 'user_id')
            ->where('likeable_type', get_class($this))
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * @return BelongsToMany
     */
    public function usersWhoShared(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shares', 'post_id', 'user_id');
    }


    /**
     * @param $value
     * @return string|null
     */
    public function getImageAttribute($value): ?string
    {
        $filePath = public_path('storage/' . $value);

        if ($value && file_exists($filePath)) {
            return asset('storage/' . $value);
        }

        return null;
    }


    /**
     * @param $value
     * @return string|null
     */
    public function getVideoAttribute($value): ?string
    {
        $filePath = public_path('storage/' . $value);

        if ($value && file_exists($filePath)) {
            return asset('storage/' . $value);
        }

        return null;
    }
}
