@extends('layouts.app')

@section ('title', 'Contact Page')
@section('content')
<h1>Contact</h1>
<p>Hello this is contact!</p>
@can('home.secret')
<p>
    <a href="{{ route('secret') }}">Go to special Contact Details</a>
</p>
@endcan
@endsection
