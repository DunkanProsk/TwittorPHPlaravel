@extends('layout')

@section('title') Login out @endsection

@section('main_content')

<img class="Content__avatar" src="{{'storage/' . $user->linkPhoto}}">
<h1 class="Content__username">{{$user->userName}}</h1>
<button class="Content__button__tweet" onclick="document.location='/loginout_check'">Login out</button>

@endsection
