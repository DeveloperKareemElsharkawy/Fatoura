<?php

namespace App\Http\Controllers\API\V1\FriendShip;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Friendship\AcceptFriendRequest;
use App\Http\Requests\API\Friendship\RejectFriendRequest;
use App\Http\Requests\API\Friendship\SendFriendRequest;
use App\Http\Resources\API\Friendship\FriendRequestResource;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Http\Resources\API\Profile\ProfileWithMutualFriendsResource;
use App\Models\FriendRequest;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class FriendController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return JsonResponse
     */
    public function getFriends()
    {
        $user = auth()->user();
        $friends = $user->friends()->paginate();

        return $this->respondWithPagination(new ProfileResource($friends));
    }


    /**
     * @param SendFriendRequest $request
     * @return JsonResponse
     */
    public function sendFriendRequest(SendFriendRequest $request)
    {
        $fromUser = auth()->user();

        $check = $fromUser->canSendFriendRequest($request->to_user_id);

        if ($check !== true) {
            return $this->respondError($check);
        }

        FriendRequest::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $request->to_user_id,
            'status' => 'pending',
        ]);


        return $this->respondMessage('Friend request has been sent successfully');
    }

    /**
     * @param AcceptFriendRequest $request
     * @return JsonResponse
     */
    public function acceptFriendRequest(AcceptFriendRequest $request)
    {
        $friendRequest = FriendRequest::query()->find($request->friend_request_id);
        $friendRequest->update(['status' => 'accepted']);

        // Create friendships From Both Sides
        $friendRequest->fromUser->friends()->attach($friendRequest->to_user_id);
        $friendRequest->toUser->friends()->attach($friendRequest->from_user_id);

        return $this->respondMessage('Friend request accepted');
    }

    /**
     * @param RejectFriendRequest $request
     * @return JsonResponse
     */
    public function rejectFriendRequest(RejectFriendRequest $request)
    {
        $friendRequest = FriendRequest::query()->findOrFail($request->friend_request_id);

        $friendRequest->update(['status' => 'rejected']);

        return $this->respondMessage('Friend request rejected');

    }

    /**
     * @return JsonResponse
     */
    public function getPendingFriendRequests()
    {
        $user = auth()->user();

        $pendingRequests = $user->pendingFriendRequests()->paginate();

        return $this->respondWithPagination(FriendRequestResource::collection($pendingRequests), 'pending friend requests');
    }


    /**
     * @return JsonResponse
     */
    public function getPendingSentFriendRequests()
    {
        $user = auth()->user();

        $pendingSentRequests = $user->pendingSentFriendRequests()->paginate();

        return $this->respondWithPagination(FriendRequestResource::collection($pendingSentRequests), 'pending friend requests');
    }


    public function suggestedFriends()
    {
        $currentUser = auth()->user();

        $potentialFriends = User::query()->potentialFriends($currentUser)->OrderByMutualFriendsCount($currentUser)->OrderByLocation($currentUser)->paginate();

        return $this->respondWithPagination(ProfileWithMutualFriendsResource::collection($potentialFriends));
    }



}
