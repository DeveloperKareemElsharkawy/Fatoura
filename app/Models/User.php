<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'profile_picture',
        'bio',
        'country_id',
        'state_id',
        'date_of_birth',
        'gender',
        'provider',
        'provider_id',
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
        'password' => 'hashed',
    ];


    /**
     * @param $value
     * @return string
     */
    public function getProfilePictureAttribute($value): string
    {
        $filePath = public_path('storage/' . $value);

        if ($value && file_exists($filePath)) {
            return asset('storage/' . $value);
        }

        // If the image doesn't exist or is not a valid image, provide a default image URL
        return asset('storage/default-user.jpg');
    }


    /**
     * @param $toUserId
     * @return string|true
     */
    public function canSendFriendRequest($toUserId): bool|string
    {

        if ($this->friends->contains($toUserId)) {
            return 'Already friends';
        }

        if ($this->pendingSentFriendRequests()->where('to_user_id', $toUserId)->exists()) {
            return 'Friend request already sent';
        }

        return true;
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }


    /**
     * @return HasMany
     */
    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'from_user_id');
    }

    /**
     * @return HasMany
     */
    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'to_user_id');
    }

    /**
     * @return HasMany
     */
    public function acceptedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'from_user_id')
            ->where('status', 'accepted');
    }

    /**
     * @return BelongsToMany
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }

    /**
     * @return HasMany
     */
    public function pendingFriendRequests(): HasMany
    {
        return $this->receivedFriendRequests()->where('status', 'pending');
    }


    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return BelongsToMany
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    /**
     * Get the posts liked by the user.
     */
    public function likedPosts(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(Post::class, 'likeable', 'likes')->withTimestamps();
    }


    /**
     * @return HasMany
     */
    public function pendingSentFriendRequests(): HasMany
    {
        return $this->sentFriendRequests()->where('status', 'pending');
    }


    /**
     * @param $query
     * @param User $currentUser
     * @return mixed
     */
    public function scopePotentialFriends($query, User $currentUser): mixed
    {
        return $query
            ->whereNotIn('id', $currentUser->friends()->pluck('id'))
            ->where('id', '!=', $currentUser->id);
    }


    /**
     * @param $query
     * @param User $currentUser
     * @return mixed
     */
    public function scopeOrderByMutualFriendsCount($query, User $currentUser): mixed
    {
        return $query->selectRaw('users.*, COUNT(mutual_friendships.friend_id) as mutual_friends_count')
            ->leftJoin('friendships as user_friendships', function ($join) {
                $join->on('users.id', '=', 'user_friendships.friend_id');
            })
            ->leftJoin('friendships as mutual_friendships', function ($join) use ($currentUser) {
                $join->on('user_friendships.user_id', '=', 'mutual_friendships.user_id')
                    ->where('mutual_friendships.friend_id', '=', $currentUser->id);
            })
            ->where('users.id', '!=', $currentUser->id)
            ->groupBy('users.id') // Include only 'id' in the GROUP BY clause
            ->orderByDesc('mutual_friends_count');
    }


    /**
     * @param $query
     * @param User $currentUser
     * @return mixed
     */
    public function scopeOrderByLocation($query, User $currentUser): mixed
    {
        return $query->orderByRaw("CASE
        WHEN users.country_id = ? OR users.state_id = ? THEN 0
        ELSE 1
        END", [$currentUser->country_id, $currentUser->state_id]);
    }
}
