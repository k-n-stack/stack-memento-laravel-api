<?php



use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email-verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::get('/login', [
    'as' => 'login',
    'uses' => function () {
        return ['status' => 'unauthenticated'];
    }
]);

Route::get('/not-verified', function () {
    return ['status' => 'not verified'];
})->name('verification.notice');

Route::get('/ressource/{type}/{anid}', [RessourceController::class, 'getAvatar']);
// Route::get('/ressource-thread/{anid}', [RessourceController::class, 'getAvatar']);

Route::get('/test', [RessourceController::class, 'test']);

Route::group([
    'middleware' => [
        'auth:sanctum',
        'verified',
    ],
], function () {
    Route::get('/user-thread-full', [ThreadController::class, 'allFullOfAuth']);
    Route::get('/user-thread-count', [ThreadController::class, 'countAllOfAuth']);
    Route::get('/user-bookmark', [BookmarkController::class, 'allOfAuth']);
    Route::get('/user-bookmark-count', [BookmarkController::class, 'countAllOfAuth']);
    Route::get('/user-redirection', [RedirectionController::class, 'allOfAuth']);
    Route::get('/user-redirection-count', [RedirectionController::class, 'countAllOfAuth']);
    Route::get('/user-comment', [CommentController::class, 'allOfAuth']);
    Route::get('/user-comment-count', [CommentController::class, 'countAllOfAuth']);
    Route::get('/user-vote-count', [VoteController::class, 'countAllOfAuth']);
    Route::get('/user-vote', [VoteController::class, 'allOfAuth']);
    Route::get('/user-pinned', [ThreadController::class, 'pinnedOfAuth']);
    Route::get('/user-image', [UserController::class, 'getUserImage']);
    Route::get('/user-subscribed-group', [GroupController::class, 'getSubscribedGroups']);
    Route::get('/user-own-group', [GroupController::class, 'getOwnedGroups']);
    Route::get('/user-fellows', [FriendController::class, 'getFellows']);
    
    Route::get('/global-thread-full', [ThreadController::class, 'allFullOfGlobal']);
    
    Route::post('/post-bookmark', [BookmarkController::class, 'postBookmark']);
    Route::post('/post-thread', [ThreadController::class, 'postThread']);
    Route::post('/post-avatar', [UserController::class, 'postAvatar']);

    
});

Route::fallback(function () {
    return ['status' => 'unauthorized'];
});
