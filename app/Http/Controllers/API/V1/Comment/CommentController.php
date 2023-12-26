<?php

namespace App\Http\Controllers\API\V1\Comment;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Comment\GetCommentsRequest;
use App\Http\Requests\API\Comment\SaveCommentRequest;
use App\Http\Requests\API\Friendship\AcceptFriendRequest;
use App\Http\Requests\API\Friendship\RejectFriendRequest;
use App\Http\Requests\API\Friendship\SendFriendRequest;
use App\Http\Requests\API\Like\ToggleLikeRequest;
use App\Http\Requests\API\Post\DeletePostRequest;
use App\Http\Requests\API\Post\SavePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Http\Resources\API\Comment\CommentResource;
use App\Http\Resources\API\Friendship\FriendRequestResource;
use App\Http\Resources\API\Post\PostResource;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Http\Resources\API\Profile\ProfileWithMutualFriendsResource;
use App\Models\Comment;
use App\Models\FriendRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class CommentController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param SaveCommentRequest $request
     * @param $commentableType
     * @param $commentableId
     * @return JsonResponse
     */
    public function saveComment(SaveCommentRequest $request, $commentableType, $commentableId)
    {
        Comment::create($request->validated());

        return $this->respondMessage('Comment saved successfully');
    }

    /**
     * @param GetCommentsRequest $request
     * @param $commentableType
     * @param $commentableId
     * @return JsonResponse
     */
    public function getComments(GetCommentsRequest $request, $commentableType, $commentableId)
    {
        $validatedData = $request->validated();

        $comments = Comment::where('commentable_type', $validatedData['commentable_type'])
            ->where('commentable_id', $validatedData['commentable_id'])
            ->with('user')
            ->paginate();

        return $this->respondWithPagination(CommentResource::collection($comments));
    }
}
