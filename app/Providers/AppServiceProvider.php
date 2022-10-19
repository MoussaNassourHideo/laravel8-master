<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use App\http\Resources\Comment as CommentResource;
// use Illuminate\Http\Resources\Json\Resource;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::aliasComponent('components.badge', 'badge');
        Blade::aliasComponent('components.updated', 'updated');
        Blade::aliasComponent('components.card', 'card');
        Blade::aliasComponent('components.tags' , 'tags');
        Blade::aliasComponent('components.errors' , 'errors');
        Blade::aliascomponent('components.comment-form','commentForm');
        Blade::aliasComponent('components.comment-list', 'commentList');
        // view()->composer('*' , ActivityComposer::class);
        view()->composer(['posts.index','posts.show'] , ActivityComposer::class);
       
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        $this->app->singleton(Counter::class , function ($app) 
         {
              return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                env('COUNTER_TIMEOUT')
            );
         });
           
        $this->app->bind(
            'App\Contracts\CounterContract',
            Counter::class
        );

    //    CommentResource::withoutWrapping();
        //   Resource::withoutWrapping();
        // $this->app->when(Counter::class)
        // ->needs('$timeout')
        // ->give(env('COUNTER_TIMEOUT'));
    }
}
