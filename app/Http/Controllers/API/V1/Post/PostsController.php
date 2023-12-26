<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Friendship\AcceptFriendRequest;
use App\Http\Requests\API\Friendship\RejectFriendRequest;
use App\Http\Requests\API\Friendship\SendFriendRequest;
use App\Http\Requests\API\Post\DeletePostRequest;
use App\Http\Requests\API\Post\SavePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Http\Resources\API\Friendship\FriendRequestResource;
use App\Http\Resources\API\Post\PostResource;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Http\Resources\API\Profile\ProfileWithMutualFriendsResource;
use App\Models\FriendRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class PostsController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $posts = Post::query()->where('user_id', auth()->user()->id)->paginate();

        return $this->respondWithPagination(PostResource::collection($posts));
    }


    /**
     * @return JsonResponse
     */
    public function store(SavePostRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadFile($request->image, 'posts', 'post_' . getNextAutoIncrementId('posts') . '.png');
        }

        if ($request->hasFile('video')) {
            $validatedData['video'] = $this->uploadFile($request->video, 'posts', 'post_' . getNextAutoIncrementId('posts') . '.mp4');
        }

        $post = Post::query()->create($validatedData);

        return $this->respondData(new PostResource($post));
    }


    /**
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request)
    {
        $post = Post::query()->find($request->post_id);

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadFile($request->image, 'posts', 'post_' . $post->id . '.png');
        }

        if ($request->hasFile('video')) {
            $validatedData['video'] = $this->uploadFile($request->video, 'posts', 'post_' . $post->id . '.mp4');
        }

        $post->update($validatedData);

        return $this->respondData(new PostResource($post));
    }


    /**
     * @return JsonResponse
     */
    public function destroy(DeletePostRequest $request)
    {
        Post::query()->where('id', $request->post_id)->delete();

        return $this->respondMessage('Post deleted successfully');
    }


    /**
     * @return JsonResponse
     */
    public function feeds()
    {
        $posts = Post::query()->latest()->paginate();

        return $this->respondWithPagination(PostResource::collection($posts));
    }


}
