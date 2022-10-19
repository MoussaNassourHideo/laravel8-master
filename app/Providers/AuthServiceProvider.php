<?php

namespace App\Providers;
use Illuminate\support\Facades\Gate;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
       ' App\Models\BlogPost' => 'App\Policies\BlogPostPolicy',
       'App\Models\User' => 'App\Policies\UserPolicy',
       'App\Comment' => 'App\Policies\CommentPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

         Gate::define('home.secret' , function($user) {
              return $user->is_admin;
         } );

        // Gate::define('update-post' , function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });

        // Gate::define('delete-post' , function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });
         
        // Gate::define('posts.update' , 'App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete' , 'App\Policies\BlogPostPolicy@delete');
        
        Gate::resource('posts' , 'App\Policies\BlogPostPolicy');
        Gate::before(function($user, $ability) {
            if($user->is_admin && in_array($ability, ['update','delete'])) {
                return true;
            }
        });
    }
}
