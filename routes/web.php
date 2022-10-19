<?php
use App\Http\Controllers\PostCommentController;
use App\http\Controllers\PostTagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\AboutController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

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


Route::get('/',[HomeController::class, 'home'])->name('home.index')
// ->middleware('auth')
;
Route::get('/contact',[HomeController::class,'contact'])->name('home.contact');
Route::get('/secret' , [HomeController::class ,'secret'])
->name('secret')
->middleware('can:home.secret')
;
Route::resource('posts' , PostsController::class);
Route::get('/posts/tag/{tag}' , [PostTagController::class , 'index'])->name('posts.tags.index');
Route::resource('posts.comments' , PostCommentController::class)->only(['index','store']);
Route::resource('users.comments' , UserCommentController::class)->only(['store']);
Route::resource('users' , UserController::class)->only(['show', 'edit','update']);

Route::get('mailable', function () {
    $comment = App\Models\Comment::find(1);
    return new App\Mail\CommentPostedMarkdown($comment);
});




Auth::routes(); 











 





