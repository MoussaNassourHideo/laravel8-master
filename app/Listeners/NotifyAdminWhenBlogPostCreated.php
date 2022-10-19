<?php

namespace App\Listeners;

use App\Events\BlogPostPosted;
use App\Jobs\ThrottledMail;
use App\Mail\BlogPostAdded;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminWhenBlogPostCreated
{
    

    public function handle(BlogPostPosted  $event)
    {
        User::thatIsAnAdmin()->get()
        ->map(function (User $user) {
             ThrottledMail::dispatch(
                new BlogPostAdded(),
                $user
             );
        });
    }
}
