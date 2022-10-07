@extends('layout')

@section('title') Twittor @endsection

@section('main_content')

<div class="Content__info">
    <div class="Content__info__name">
        <img class="Content__avatar" src="{{'storage/' . $user->linkPhoto}}">
        <h1 class="Content__username">{{$user->userName}}</h1>
        <button class="Content__button__tweet" onclick="document.location='/newtweet'">TWEET</button>
    </div>
    <div class="Content__info__bio">
        {{$user->userBio}}
    </div>
    <div class="Content__follows">
        <div class="Content__follows__followers">Followers: {{$followers}}</div>
        <div class="Content__follows__following">Following: {{$following}}</div>
    </div>
</div>

<h2 class="Content__title">Tweets:</h2>

<div class="Content__swap__tweet" >
    <form class="Content__form__swap" enctype="multipart/form-data" action="/" method="POST">
        @csrf
        <input type="hidden" name="sort" value="all">
        <button class="Content__swap__button1" type="submit" @if($sort == 'user' || $sort == 'following') style='font-weight:300;' @endif>ALL</button><br>
    </form>
    <form class="Content__form__swap" enctype="multipart/form-data" action="/" method="POST">
        @csrf
        <input type="hidden" name="sort" value="following">
        <button class="Content__swap__button3" type="submit" @if($sort == 'following') style='font-weight:900;' @endif>FOLLOWING</button><br> 
    </form>
    <form class="Content__form__swap" enctype="multipart/form-data" action="/" method="POST">
        @csrf
        <input type="hidden" name="sort" value="user">
        <button class="Content__swap__button2" type="submit" @if($sort == 'user') style='font-weight:900;' @endif>YOUR</button><br>
    </form>
</div>

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
                {!!"<a href=\"" . "/" . $tweet->userNickname . "\">" . "@" . $tweet->userNickname . "</a>"!!}
            </div>
            <div class="Content__date__tweets">{{$tweet->tweetDateCreate}}</div>
            @if ($user->id == $tweet->idCreator)
                <div class="Content__icons">
                    <?php $str = "id_tweet=" . $tweet->id?>
                    <img class="iconDelete" src="storage/img/RectangleIcon.png" onclick="document.location='/tweetDelete?<?=$str?>'">
                </div>
            @endif
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
