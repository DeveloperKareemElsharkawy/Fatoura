<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Friendship\AcceptFriendRequest;
use App\Http\Requests\API\Friendship\RejectFriendRequest;
use App\Http\Requests\API\Friendship\SendFriendRequest;
use App\Http\Requests\API\Like\ToggleLikeRequest;
use App\Http\Requests\API\Post\DeletePostRequest;
use App\Http\Requests\API\Post\SavePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Http\Resources\API\Friendship\FriendRequestResource;
use App\Http\Resources\API\Post\PostResource;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Http\Resources\API\Profile\ProfileWithMutualFriendsResource;
use App\Models\FriendRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class LikePostController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param $postId
     * @param ToggleLikeRequest $request
     * @return JsonResponse
     */
    public function toggleLike($postId, ToggleLikeRequest $request)
    {
        $post = Post::query()->find($postId);

        $post->toggleLike();

        return $this->respondData([
            new PostResource($post)
        ], 'Like toggled successfully');
    }

    /**
     * @return JsonResponse
     */
    public function likedPosts()
    {
        $user = auth()->user();

        $likedPosts = $user->likedPosts()->paginate(10);

        return $this->respondWithPagination(PostResource::collection($likedPosts));
    }

    /**
     * @return JsonResponse
     */
    public function PostLikes($postId, ToggleLikeRequest $request)
    {
        $post = Post::query()->find($postId);

        $likedUsers = $post->likedByUsers()->paginate(); // Paginate with 10 users per page

        return $this->respondWithPagination(ProfileResource::collection($likedUsers));
    }
}
