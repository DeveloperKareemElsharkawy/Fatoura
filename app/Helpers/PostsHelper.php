<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('sharedByCurrentUser')) {
    function sharedByCurrentUser($post)
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return false;
        }

        return $post->shares()->where('user_id', $currentUser->id)->exists();
    }
}

