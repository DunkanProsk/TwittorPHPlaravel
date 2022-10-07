@extends('layout')

@section('title') Sign In @endsection

@section('main_content')

<form class="Content__form" action="/signin/check" method="POST">
    <h3>Sign in</h3>

    @if ($errors->any())
        <div>
            Поля заполненны некорректно
        </div>
        <br>
    @endif

    @csrf
    <input placeholder="Your Nickname" id="nickname" name="nickname" type="text" size="40"><br>
    <input placeholder="Your Password" id="pass" name="pass" type="password" size="40"><br>
    <button class="Content__form__button" type="submit">SEND</button><br>
    <a href="/signup">Sign Up</a>
</form>
    
@endsection