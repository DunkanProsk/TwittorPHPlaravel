<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use App\Models\Hashtag;
use App\Models\HashtagTweet;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Models\Retweet;

class MainController extends Controller
{
    public function home (Request $request) {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }

        $followers = 0;
        $following = 0;

        $userFollowing = DB::table('follows')->where('idUser', session()->get('id'))->get();
        $userFollowers = DB::table('follows')->where('idFollower', session()->get('id'))->get();

        foreach ($userFollowing as $key) {
            $following++;
        }

        foreach ($userFollowers as $key) {
            $followers++;
        }

        $db = DB::table('tweets')
                        ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                        ->leftJoin('retweet', 'retweet.idTweet', '=', 'tweets.id')
                        ->orderBy('tweets.tweetDateCreate', 'desc')
                        ->select('tweets.*', 'retweet.idRetweet', 'users.userName', 'users.userNickname', 'users.linkPhoto')
                        ->get();
        $sort = 'all';

        if ($request->sort == "user") {
            $db = DB::table('tweets')
                        ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                        ->leftJoin('retweet', 'retweet.idTweet', '=', 'tweets.id')
                        ->where('tweets.idCreator', '=', session()->get('id'))
                        ->orderBy('tweets.tweetDateCreate', 'desc')
                        ->select('tweets.*', 'retweet.idRetweet', 'users.userName', 'users.userNickname', 'users.linkPhoto')
                        ->get();
            $sort = 'user';
        }

        if($request->sort == "following") {
            $selectFollow = DB::table('follows')
                        ->where('idUser','=', session()->get('id'))
                        ->select('follows.idFollower')
                        ->get()
                        ->toArray();


            $selectFollowSort = [];

            foreach($selectFollow as $key) {
                array_push($selectFollowSort, $key->idFollower);
            }

            $db = DB::table('tweets')
                        ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                        ->leftJoin('retweet', 'retweet.idTweet', '=', 'tweets.id')
                        ->whereIn('tweets.idCreator', $selectFollowSort)
                        ->orderBy('tweets.tweetDateCreate', 'desc')
                        ->select('tweets.*', 'retweet.idRetweet', 'users.userName', 'users.userNickname', 'users.linkPhoto')
                        ->get();
            $sort = 'following';
        }

        return view(
            'home',
            [
                'tweets' => $db,
                'user' => DB::table('users')->where('id', session('id'))->get()[0],
                'followers' => $followers,
                'following' => $following,
                'sort' => $sort,
            ]
        );
    }

    public function signup () {
        return view('signup');
    }

    public function signup_check (Request $request) {

        $request->validate([
            'name' => 'required|min:4|max:50',
            'nickname' => 'required|min:4|max:50',
            'pass' => 'required|min:4|max:100',
            'bio' => 'required|min:4|max:200',
            'photo' => 'required|image:jpg, jpeg, png'
        ]);

        $file = $request->file('photo')->store('img');

        $userCreate = new User();
        $userCreate->userName = $request->name;
        $userCreate->userNickname = $request->nickname;
        $userCreate->passHash = Hash::make($request->pass);
        $userCreate->userBio = $request->bio;
        $userCreate->linkPhoto = $file;

        $userCreate->save();

        $id = DB::table('users')->where('userNickname', "$request->nickname")->value('id');

        session(['id' => "$id"]);

        return redirect()->route('home');

    }

    public function signin () {
        if (session()->get('id') !== null) {
            return redirect()->route('loginout');
        }
        return view('signin');
    }

    public function loginout () {
        return view('loginout', [
            'user' => DB::table('users')->where('id', session('id'))->get()[0]
        ]);
    }

    public function loginout_check (Request $request) {
        $request->session()->flush();
        return redirect()->route('signin');
    }
    

    public function signin_check (Request $request) {

        $request->validate([
            'nickname' => 'required|min:4|max:50',
            'pass' => 'required|min:4|max:100'
        ]);

        $nickname = DB::table('users')->where('userNickname', "$request->nickname")->value('userNickname');

        if (!empty($nickname)) {

            $passHash = DB::table('users')->where('userNickname', "$request->nickname")->value('passHash');
            $passUser = $request->pass;
            
            if (Hash::check($passUser, $passHash)) {

                $id = DB::table('users')->where('userNickname', "$request->nickname")->value('id');
                session(['id' => "$id"]);
                return redirect()->route('home');

            } else {
                return "Неверный пароль";
            }
        } else {
            return "Такого пользователя не существует";
        }

    }

    public function newTweet () {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }
        return view('newtweet');
    }

    public function createNewTweet (Request $request) {

        $request->validate([
            'text' => 'required|min:3|max:255'
        ]);

        $tweetCreate = new Tweet();
        $tweetCreate->idCreator = $request->session()->get('id');
        $tweetCreate->tweetText = $request->text;
        $tweetCreate->tweetDateCreate = now();

        $tweetHashtags = Helper::getHashtags($request->text);

        $tweetCreate->save();

        $lastTweetId = Tweet::latest('id')->first('id')->toArray()['id'];

        if ($tweetHashtags) {
            $this->createHashtag($tweetHashtags, $lastTweetId);
        }

        return redirect()->route('home');
    }

    public function deleteTweet(Request $request) {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }

        DB::table('tweets')->where('id', '=', $request->id_tweet)->delete();
        return redirect()->route('home');
    }

    public function userProfile ($nickname) {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }

        $userId = DB::table('users')->where('userNickname', $nickname)->value('id');

        if (session()->get('id') == $userId) {
            return redirect()->route('home');
        }

        $follow = 0;
        $userFollow = DB::table('follows')->where('idUser', session()->get('id'))->where('idFollower', $userId)->get()->toArray();

        if(!empty($userFollow)) {
            $follow = 1;
        }

        return view('user', [
            'user' => DB::table('users')->where('userNickname', $nickname)->get()[0],
            'tweets' =>
                    DB::table('tweets')
                        ->where('userNickname', $nickname)
                        ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                        ->leftJoin('retweet', 'retweet.idTweet', '=', 'tweets.id')
                        ->orderBy('tweets.tweetDateCreate', 'desc')
                        ->select('tweets.*', 'retweet.*', 'users.userName', 'users.userNickname', 'users.linkPhoto')
                        ->get(),
            'follow' => $follow,
        ]);
    }

    public function userFollow (Request $request) {
    
        $userFollow = DB::table('follows')->where('idUser', session()->get('id'))->where('idFollower', $request->id_user)->get()->toArray();

        if(empty($userFollow)) {
            $followCreate = new Follow();
            $followCreate->idUser = session()->get('id');
            $followCreate->idFollower = $request->id_user;

            $followCreate->save();
        }

        return redirect()->route("user", $request->nick_user);
    }

    public function userUnfollow (Request $request) {
        $userFollow = DB::table('follows')->where('idUser', session()->get('id'))->where('idFollower', $request->id_user)->get()->toArray();

        if(!empty($userFollow)) {
            DB::table('follows')->where('idUser', session()->get('id'))->where('idFollower', $request->id_user)->delete();
        }

        return redirect()->route("user", $request->nick_user);
    }

    public function createHashtag($arr, $idTweet) {
        foreach ($arr as $tag) {

            $tags = DB::table('hashtags')->where('title', $tag)->value('id');

            if (!$tags) {
                $tagCreate = new Hashtag();
                $tagCreate->title = $tag;
                $tagCreate->save();
            }

            $idLastTag = DB::table('hashtags')->where('title', $tag)->value('id');

            $tagtweetCreate = new HashtagTweet();
            $tagtweetCreate->idHashtag = $idLastTag;
            $tagtweetCreate->idTweet = $idTweet;
            $tagtweetCreate->save();
            
        }
    }

    public function hashtags (Request $request) {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }

        $idHashtag = DB::table('hashtags')->where('title', "#" . $request->get('hashtag'))->value('id');
        $idTweets = DB::table('hashtag_tweet')->where('idHashtag', $idHashtag)->get('idTweet')->toArray();

        $idTweetsArr = [];

        foreach ($idTweets as $key) {
            array_push($idTweetsArr, $key->idTweet);
        }
        
        $tweets = DB::table('tweets')
            ->whereIn('tweets.id', $idTweetsArr)
            ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
            ->orderBy('tweets.tweetDateCreate', 'desc')
            ->select('tweets.*', 'users.userName', 'users.userNickname', 'users.linkPhoto')
            ->get()
        ;
        
        return view('hashtag', ['tweets' => $tweets]);
    }

    public function newRetweet (Request $request) {
        if (session()->get('id') === null) {
            return redirect()->route('signin');
        }

        return view('newretweet', [
            'tweet' =>
                    DB::table('tweets')
                        ->where('tweets.id', $request->tweet_id)
                        ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                        ->select('tweets.*', 'users.userName', 'users.userNickname', 'users.linkPhoto')
                        ->get()
        ]);
    }


    public function createRetweet (Request $request) {
        $valid = $request->validate([
            'text' => 'required|min:3|max:255'
        ]);

        $tweetCreate = new Tweet();
        $tweetCreate->idCreator = $request->session()->get('id');
        $tweetCreate->tweetText = $request->text;
        $tweetCreate->tweetDateCreate = now();

        $tweetHashtags = Helper::getHashtags($request->text);

        $tweetCreate->save();

        $lastTweetId = Tweet::latest('id')->first('id')->toArray()['id'];

        if ($tweetHashtags) {
            $this->createHashtag($tweetHashtags, $lastTweetId);
        }

        $retweetCreate = new Retweet();
        $retweetCreate->idTweet = $lastTweetId;
        $retweetCreate->IdRetweet = $request->id_retweet;

        $retweetCreate->save();

        return redirect()->route('home');
    }

}
