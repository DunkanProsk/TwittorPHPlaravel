@extends('layout')

@section('title') New Reweet @endsection

@section('main_content')

@foreach ($tweet as $twt)

<div class="Content__tweets__retweet">
    <div>
        <img class="Content__avatar__tweets" src="{{'storage/' . $twt->linkPhoto}}">
    </div>
    <div>
        <div class="Content__info__tweets">
            <div class="Content__username__tweets">{{$twt->userName}}</div>
            <div class="Content__nickname__tweets">
                {!!"<a href=\"" . "/" . $twt->userNickname . "\">" . "@" . $twt->userNickname . "</a>"!!}
            </div>
            <div class="Content__date__tweets">{{$twt->tweetDateCreate}}</div>
        </div>
        <div class="Content__text__tweets">
            {!!App\Http\Helpers\Helper::getUrlHashtag($twt->tweetText)!!}
        </div>
    </div>
</div>

@endforeach

<form class="Content__form__tweets" action="/create_retweet" method="POST">
    <h3>Create new Retweet</h3>
    @csrf
    <input type="hidden" name="id_retweet" value="{{$twt->id}}">
    <textarea class="Content__form__textaria" placeholder="Your text" name="text" id="text" type="text" size="40"></textarea><br>
    <button class="Content__form__button" type="submit">SEND</button><br>
</form>
    
@endsection