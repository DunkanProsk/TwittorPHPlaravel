@extends('layout')

@section('title') New tweet @endsection

@section('main_content')

<form class="Content__form__tweets" action="/newtweet/create" method="POST">
    <h3>Create new tweet</h3>
    @csrf
    <textarea class="Content__form__textaria" placeholder="Your text" name="text" id="text" type="text" size="40"></textarea><br>
    <button class="Content__form__button" type="submit">SEND</button><br>
</form>
    
@endsection