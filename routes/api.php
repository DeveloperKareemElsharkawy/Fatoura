<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Auth\ForgetPasswordController;
use App\Http\Controllers\API\V1\Auth\LocationController;
use App\Http\Controllers\API\V1\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\FriendShip\FriendController;
use App\Http\Controllers\API\V1\Post\PostsController;
use App\Http\Controllers\API\V1\Post\LikePostController;
use App\Http\Controllers\API\V1\Post\SharePostController;
use App\Http\Controllers\API\V1\Comment\CommentController;
use App\Http\Controllers\API\V1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('social', [AuthController::class, 'social']);
    Route::get('logout', [AuthController::class, 'logOut'])->middleware('auth:api');

    Route::post('send-reset-code', [ForgetPasswordController::class, 'sendResetCode']);
    Route::post('reset-password', [ResetPasswordController::class, 'setNewPassword']);
});

Route::prefix('location')->group(function () {
    Route::get('countries', [LocationController::class, 'getCountries']);
    Route::get('countries/{countryId}/states', [LocationController::class, 'getStates']);
});

Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);
    Route::post('/', [ProfileController::class, 'updateProfile']);
    Route::post('/update-password', [ProfileController::class, 'updatePassword']);
});

Route::prefix('friendships')->group(function () {
    Route::get('/', [FriendController::class, 'getFriends']);
    Route::get('/get-pending-friend-requests', [FriendController::class, 'getPendingFriendRequests']);
    Route::get('/get-pending-sent-friend-requests', [FriendController::class, 'getPendingSentFriendRequests']);
    Route::get('/suggested-friends', [FriendController::class, 'suggestedFriends']);

    Route::post('/send-friend-request', [FriendController::class, 'sendFriendRequest']);
    Route::post('/accept-friend-request', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/reject-friend-request', [FriendController::class, 'rejectFriendRequest']);
});

Route::prefix('posts')->group(function () {
    //  Crud Operations
    Route::get('/', [PostsController::class, 'index']);
    Route::post('/', [PostsController::class, 'store']);
    Route::post('/{postId}/update', [PostsController::class, 'update']);
    Route::post('/{postId}/delete', [PostsController::class, 'destroy']);

    //  Interactions

    // likes
    Route::post('/{postId}/toggle-like', [LikePostController::class, 'toggleLike']);
    Route::get('/liked-posts', [LikePostController::class, 'likedPosts']);
    Route::get('/{postId}/post-likes', [LikePostController::class, 'PostLikes']);


    // Comments
    Route::post('/{commentableType}/{commentableId}/comments', [CommentController::class, 'saveComment']);
    Route::get('/{commentableType}/{commentableId}/comments', [CommentController::class, 'getComments']);

    // Share
    Route::post('/{postId}/share', [SharePostController::class, 'sharePost']);
    Route::get('/{postId}/shares', [SharePostController::class, 'shares']);

});
Route::get('/feeds', [PostsController::class, 'feeds']);
