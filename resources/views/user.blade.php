@extends('layout')

@section('title') Twittor @endsection

@section('main_content')

<div class="Content__info">
    <div class="Content__info__name">
        <img class="Content__avatar" src="{{'storage/' . $user->linkPhoto}}">
        <h1 class="Content__username">{{$user->userName}}</h1>
        @if ($follow === 0)
            <form action="/{{$user->userNickname}}/follow" method="POST">
                @csrf
                <input type="hidden" name="id_user" value="{{$user->id}}">
                <input type="hidden" name="nick_user" value="{{$user->userNickname}}">
                <button type="submit" class="Content__button__tweet">FOLLOW</button>
            </form>            
        @else
            <form action="/{{$user->userNickname}}/unfollow" method="POST">
                @csrf
                <input type="hidden" name="id_user" value="{{$user->id}}">
                <input type="hidden" name="nick_user" value="{{$user->userNickname}}">
                <button type="submit" class="Content__button__tweet__unfollow">UNFOLLOW</button>
            </form>
        @endif
    </div>
    <div class="Content__info__bio">
        {{$user->userBio}}
    </div>
</div>

<h2 class="Content__title">Tweets:</h2>

@foreach ($tweets as $tweet)

<div class="Content__tweets">
    <div>
        <img class="Content__avatar__tweets" src="{{'storage/' . $tweet->linkPhoto}}">
        @if ($tweet->idRetweet)
            <img class="Content__icon__tweets" src="storage/img/RetweetUnderPhoto.svg">
        @endif
    </div>
    <div>
        <div class="Content__info__tweets">
            <div class="Content__username__tweets">{{$tweet->userName}}</div>
            <div class="Content__nickname__tweets">
                {!!"<a href=\"" . $tweet->userNickname . "\">" . "@" . $tweet->userNickname . "</a>"!!}
            </div>
            <div class="Content__date__tweets">{{$tweet->tweetDateCreate}}</div>
        </div>
        @if ($tweet->idRetweet)
        
        <?php $retweet = App\Http\Helpers\Helper::getTweetForRetweet($tweet->idRetweet); ?>

        @foreach ($retweet as $rtw)

        <div>
            <div class="Content__tweets__retweet__index">
                <div>
                    <img class="Content__avatar__tweets" src="{{'storage/' . $rtw->linkPhoto}}">
                </div>
                <div>
                    <div class="Content__info__tweets">
                        <div class="Content__username__tweets">{{$rtw->userName}}</div>
                        <div class="Content__nickname__tweets">
                            {!!"<a href=\"" . "/" . $rtw->userNickname . "\">" . "@" . $rtw->userNickname . "</a>"!!}
                        </div>
                        <div class="Content__date__tweets">{{$rtw->tweetDateCreate}}</div>
                    </div>
                    <div class="Content__text__tweets">
                        {!!App\Http\Helpers\Helper::getUrlHashtag($rtw->tweetText)!!}
                    </div>
                </div>
            </div>
        </div>

        @endforeach

        @endif

        <div class="Content__text__tweets">
            {!!App\Http\Helpers\Helper::getUrlHashtag($tweet->tweetText)!!}
        </div>

        <div>
            <form action="/retweet" method="POST">
                @csrf
                <input type="hidden" name="tweet_id" value="{{$tweet->id}}">
                <button class="iconRetweet" type="submit"></button>
            </form>
        </div>
    </div>
</div>

@endforeach
    
@endsection
