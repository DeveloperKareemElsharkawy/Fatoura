<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\User;

if (!function_exists('getMutualFriends')) {
    /**
     * Get the first 10 mutual friends between two users.
     *
     * @param User $currentUser
     * @param User $otherUser
     * @param int $limit
     * @return Collection
     */
    function getMutualFriends(User $currentUser, User $otherUser, int $limit = 10): Collection
    {
        $currentUserFriendIds = $currentUser->friends()->pluck('id')->toArray();
        $otherUserFriendIds = $otherUser->friends()->pluck('id')->toArray();

        $mutualFriendIds = array_intersect($currentUserFriendIds, $otherUserFriendIds);

        return User::whereIn('id', $mutualFriendIds)->take($limit)->get();
    }
}

if (!function_exists('getTotalMutualFriendsCount')) {
    /**
     * Get the count of all mutual friends between two users.
     *
     * @param User $currentUser
     * @param User $otherUser
     * @return int
     */
    function getTotalMutualFriendsCount(User $currentUser, User $otherUser): int
    {
        $currentUserFriendIds = $currentUser->friends()->pluck('id')->toArray();
        $otherUserFriendIds = $otherUser->friends()->pluck('id')->toArray();

        $mutualFriendIds = array_intersect($currentUserFriendIds, $otherUserFriendIds);

        return count($mutualFriendIds);
    }
}
