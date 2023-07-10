<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/* Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/chats', [ChatController::class, 'index'])->name('chats');

    Route::post('/createRoom', [ChatController::class, 'createRoom'])->name('chats.createRoom');

    Route::post('/chatMessages', [ChatController::class, 'getMessages'])->name('chats.messages');

    Route::post('/sendMessage', [ChatController::class, 'sendMessage'])->name('chats.send');

    Route::post('/updateMessage', [ChatController::class, 'updateMessage'])->name('chats.updateMessage');

    Route::delete('/deleteMessage/{chatId}', [ChatController::class, 'deleteMessage'])->name('chats.updateMessage');

    Route::post('/posts', [PostController::class, 'posts']);

    Route::get('/post/{postId}', [PostController::class, 'post']);

    Route::post('/createPost', [PostController::class, 'create']);

    Route::post('/updatePost', [PostController::class, 'update']);

    Route::delete('/deletePost/{postId}', [PostController::class, 'delete']);

    Route::get('/followers/{userId}', [FollowerController::class,'followers']);

    Route::get('/followUser/{userId}', [FollowerController::class,'followUser']);

    Route::get('/unfollowUser/{userId}', [FollowerController::class,'unfollowUser']);

    Route::get('/suggestedFollowers', [FollowerController::class,'suggestedFollowers']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{profileId}', [ProfileController::class, 'profile']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
