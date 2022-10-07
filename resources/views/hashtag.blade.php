@extends('layout')

@section('title') Hashtag @endsection

@section('main_content')

<h2 class="Content__title">Tweets #{{$_GET['hashtag']}}:</h2>

@foreach ($tweets as $tweet)

<div class="Content__tweets">
    <div>
        <img class="Content__avatar__tweets" src="{{'storage/' . $tweet->linkPhoto}}">
    </div>
    <div>
        <div class="Content__info__tweets">
            <div class="Content__username__tweets">{{$tweet->userName}}</div>
            <div class="Content__nickname__tweets">
                {!!"<a href=\"" . "/" . $tweet->userNickname . "\">" . "@" . $tweet->userNickname . "</a>"!!}
            </div>
            <div class="Content__date__tweets">{{$tweet->tweetDateCreate}}</div>
        </div>
        <div class="Content__text__tweets">
            {!!App\Http\Helpers\Helper::getUrlHashtag($tweet->tweetText)!!}
        </div>
    </div>
</div>

@endforeach
    
@endsection
