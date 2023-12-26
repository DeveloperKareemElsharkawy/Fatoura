<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likable
{
    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return bool
     */
    public function toggleLike(): bool
    {
        $userId = auth()->id();

        if ($this->isLikedByUser($userId)) {
            $this->likes()->where('user_id', $userId)->delete();
            return false;
        } else {
            $this->likes()->create(['user_id' => $userId]);
            return true;
        }
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isLikedByUser(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * @return bool
     */
    public function isLikedByCurrentUser(): bool
    {
        return $this->isLikedByUser(auth()->id());
    }
}
