<?php

namespace App\Http\Controllers;

use App\Events\BlogPostPosted;

use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\Image;
use App\Services\Counter;
use Illuminate\support\Facades\Gate;
use Illuminate\support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Contracts\CounterContract;
use App\Facades\CounterFacade;

class PostsController extends Controller
{
     private $counter;
    public function __construct(CounterContract $counter)
    {
        $this->middleware('auth')->only(['create' , 'store' ,'edit' , 'update' , 'destroy']);
        // $this->middleware('locale');
        $this->counter = $counter;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
      
        return view(
            'posts.index' , 
            [
                'posts' => BlogPost::latestWithRelations()->get(),
                
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create') ;
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedData);

       
        if ($request->hasFile('thumbnail'))  {

            $path = $request->file('thumbnail')->store('public/thumbnails');
            $blogPost->image()->save(
                Image::make(['path' => $path ])
            );
            

        }
        
        event(new BlogPostPosted($blogPost));

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    $blogPost = Cache::remember("blog-post-{$id}", 60 , function() use($id) {
           return BlogPost::with('comments' , 'tags' , 'user' , 'comments.user')
           ->with('tags')
           ->with('user')
           ->with('comments.user')
           ->findOrFail($id);
    });
     
    // $counter = resolve(Counter::class);
       

    return view('posts.show' , [
        'post' => $blogPost ,
        'counter' => CounterFacade::increment("blog-post-{$id}" , ['blog-post']),
    ]);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update' , $post);
       return view('posts.edit' , ['post' => BlogPost::findOrFail($id)]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
    //    if( Gate::denies('update-post' , $post )){
    //     abort(403 , "you cant edit this blog post!");
    //    }
        $this->authorize('update' , $post);
        $validated = $request->validated();
        $post->fill($validated);

        if ($request->hasFile('thumbnail'))  {

            $path = $request->file('thumbnail')->store('public/thumbnails');

            if($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path ;
                $post->image->save();
            } else {
               
                $post->image()->save(
                    Image::make(['path' => $path ])
                );
            }
            
            

        }
        $post->save();

        $request->session()->flash('status' , 'Blog post was updated !');
        return redirect()->route('posts.show', ['post'=> $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id ,Request $request)
    {
        $post = BlogPost::findOrFail($id);

        // if( Gate::denies('delete-post' , $post )){
        //     abort(403 , "you can't delete this blog post!");
        //    }
        $this->authorize('delete' , $post);
        $post->delete();

        session()->flash('status' , 'Blog post was deleted !');

        return redirect()->route('posts.index');
    }
}
