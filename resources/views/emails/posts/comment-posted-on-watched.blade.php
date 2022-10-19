@component('mail::message')
# Comment was posted on post you 're watching'

hi {{ $user->name }}

@component('mail::button', ['url' =>  route('posts.show' , ['post' => $comment->commentable->id]) ])
View Thee Blog Post
@endcomponent

@component('mail::button', ['url' =>  route('posts.show' , ['post' => $comment->commentable->id]) ])
Visit   {{ route('users.show', ['user' => $comment->user->id]) }}  profile
@endcomponent 


@component('mail::panel')
    {{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

