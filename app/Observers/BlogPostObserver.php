<?php

namespace App\Observers;

use App\Models\BlogPost;

class BlogPostObserver
{
    

    /**
     * Handle the BlogPost "updated" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function updated(BlogPost $blogPost)
    {
        //
    }


    public function updating(BlogPost $blogPost)
    {
        
    }
    

    public function deleting(BlogPost $blogPost)
    {
        $blogPost->comments()->delete();
        $blogPost->image()->delete();
    }

    

    public function restoring(BlogPost $blogPost)
    {
        $blogPost->comments()->restore();
    }
    

    
}
