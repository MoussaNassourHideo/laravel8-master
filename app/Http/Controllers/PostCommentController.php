<?php

namespace App\Http\Controllers;
use App\Models\BlogPost;
use App\Http\Requests\StoreComment;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use App\http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{

   public function __construct()
   {
     $this->middleware('auth:web')->only(['store']);
   }

   public function index(BlogPost $post)
   {
       return  CommentResource::collection($post->comments()->with('user')->get());
      //  return $post->comments()->with('user')->get();

   }

    public function store(BlogPost $post , StoreComment $request) 
    {
    //    Comment::create()
    $comment =   $post->comments()->create([
          'content' => $request->input('content'),
          'user_id' => $request->user()->id
      ]);
       
    event(new CommentPosted($comment));

    

           return redirect()->back()
           ->withStatus('comment Was created!');
    // Mail::to($post->user)->send(
    //   new CommentPosted($comment)
    // );
      // $when = now()->addMinutes(1);
    // Mail::to($post->user)->queue(
    //   new CommentPostedMarkdown($comment)
    // );

    // Mail::to($post->user)->later(
    //   $when,
    //   new CommentPostedMarkdown($comment)
    // );

    NotifyUsersPostWasCommented::dispatch($comment);


      return redirect()->back()
      ->withStatus('Comment was created !');
    }

   
}
