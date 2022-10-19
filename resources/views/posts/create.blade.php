@extends('layouts.app')

@section('title','Create the post')

@section('content')

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="mt-2">
    @csrf
    @include('posts.partials.form')
    <div class="mt-2">
        <input type="submit" value="Create" class="btn btn-primary btn-block mt-2"></div>
</form>
@endsection