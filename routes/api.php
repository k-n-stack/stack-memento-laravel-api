<?php



use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RedirectionController;
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

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::get('/login', [
    'as' => 'login',
    'uses' => function () {
        return ['status' => 'unauthenticated'];
    }
]);

Route::get('/test', function () {
    return ['get' => 'foo'];
});
Route::post('/test', function () {
    return ['post' => 'bar'];
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => [
        'auth:sanctum',
    ],
], function () {
    Route::get('/testauth', function () { return "hello"; });
    
    Route::apiResources([
        'bookmarks' => BookmarkController::class,
        'comments' => CommentController::class,
        'friends' => FriendController::class,
        'groups' => GroupController::class,
        'redirections' => RedirectionController::class,
        'searches' => SearchController::class,
        'tags' => TagController::class,
        'threads' => ThreadController::class,
        'users' => UserController::class,
        'votes' => VoteController::class,
    ]);

    Route::get('/user-thread-count', [ThreadController::class, 'countAllOfAuth']);
    Route::get('/user-bookmark', [BookmarkController::class, 'allOfAuth']);
    Route::get('/user-bookmark-count', [BookmarkController::class, 'countAllOfAuth']);
    Route::get('/user-redirection', [RedirectionController::class, 'allOfAuth']);
    Route::get('/user-redirection-count', [RedirectionController::class, 'countAllOfAuth']);
    Route::get('/user-comment', [CommentController::class, 'allOfAuth']);
    Route::get('/user-comment-count', [CommentController::class, 'countAllOfAuth']);

});

Route::fallback(function () {
    return ['status' => 'unauthorized'];
});
