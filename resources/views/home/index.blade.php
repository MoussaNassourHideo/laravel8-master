@extends('layouts.app')

@section ('title', 'home Page')
@section('content')
<h1>{{ __('messages.welcome') }}</h1>
<h1>@lang('messages.welcome')</h1>

<p>{{ __('message.example_with_value', ['name' => 'John']) }}</p>

<p>{{ trans_choice('messages.plural', 0) }}</p>
<p>{{ trans_choice('messages.plural', 1) }}</p>
<p>{{ trans_choice('messages.plural', 2) }}</p>

<p>Using Json: {{ __('Welcome to laravel!') }}</p>
<p>Using Json: {{ __('Hello :name' , ['name' => 'Piotr']) }}</p>
<p>This is the content of the main page!</p>
@endsection