<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Friendship\AcceptFriendRequest;
use App\Http\Requests\API\Friendship\RejectFriendRequest;
use App\Http\Requests\API\Friendship\SendFriendRequest;
use App\Http\Requests\API\Like\ToggleLikeRequest;
use App\Http\Requests\API\Post\DeletePostRequest;
use App\Http\Requests\API\Post\SavePostRequest;
use App\Http\Requests\API\Post\SharePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Http\Resources\API\Friendship\FriendRequestResource;
use App\Http\Resources\API\Post\PostResource;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Http\Resources\API\Profile\ProfileWithMutualFriendsResource;
use App\Models\FriendRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class SharePostController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param $postId
     * @param SharePostRequest $request
     * @return JsonResponse
     */
    public function sharePost($postId, SharePostRequest $request)
    {
        $post = Post::query()->find($postId);

        Share::firstOrCreate([
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
        ]);

        return $this->respondMessage('Share post successfully');
    }

    /**
     * @param $postId
     * @param SharePostRequest $request
     * @return JsonResponse
     */
    public function shares($postId, SharePostRequest $request)
    {
        $post = Post::query()->find($postId);

        $users = $post->usersWhoShared()->paginate();

        return $this->respondWithPagination(ProfileResource::collection($users));
    }
}
