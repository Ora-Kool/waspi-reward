<?php

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Comment;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('posts')->group(function () {
    Route::controller(PostController::class)->group(function () {
        Route::post('/store', 'storePost')->name('post.store');
        Route::get('/{post:title}/like', 'likePost')->name('post.like');
        Route::post('/{post:title}/comment', 'storeComment')->name('comment.store');
        Route::delete('/comments/{comment}', 'deleteComment')->name('comment.delete');
    });
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        "posts" => Post::all()->map(fn($post) => [
            "user" => $post->user->name,
            "title" => $post->title,
            "message" => $post->message,
            "date" => now()->toFormattedDateString(),
            "comments" => count($post->comments) > 0 ? count($post->comments) : 0,
            "likes" => count($post->likes) ? count($post->likes) : 0,
            "isLiked" => $post->likes->contains(function (object $like, int $key) {
                return $like->user_id === auth()->user()->id;
            }),
            "badges" => collect($post->user->badges)->map(fn($badge) => [
                "id" => $badge->id
            ])
        ]),
        "comments" => Comment::all()->map(fn($comment) => [
            "id" => $comment->id,
            "message" => $comment->message,
            "createdAt" => $comment->created_at->longRelativeDiffForHumans(),
            "user" => $comment->user->name
        ]),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';