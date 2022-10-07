@extends('layout')

@section('title') Sign Up @endsection

@section('main_content')

<form class="Content__form" enctype="multipart/form-data" action="/signup/check" method="POST">
    <h3>Sign Up</h3>

    @if ($errors->any())
        <div>
            Поля заполненны некорректно
        </div>
        <br>
    @endif

    @csrf
    <input placeholder="Your Name" id="name" name="name" type="text" size="40"><br>
    <input placeholder="Your Nickname" id="nickname" name="nickname" type="text" size="40"><br>
    <input placeholder="Your Password" id="pass" name="pass" type="password" size="40"><br>
    <textarea placeholder="About You" id="bio" name="bio" type="text" size="40"></textarea><br>
    <input class="Content__form__file" id="photo" name="photo" type="file" extension="png,jpg"><br>
    <button class="Content__form__button" type="submit">SEND</button><br>

    <a href="/signin">Login in</a>
</form>
    
@endsection