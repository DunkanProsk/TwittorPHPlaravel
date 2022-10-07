<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getHashtags($str) {
        $hashtags = false;
        preg_match_all("/(#\w+)/u", $str, $tags);

        if ($tags) {
            $hashtagsArray = array_count_values($tags[0]);
            $hashtags = array_keys($hashtagsArray);
        }

        return $hashtags;
    }

    public static function getUrlHashtag($tweet) {
        $tags = Helper::getHashtags($tweet);
        $text = $tweet;

        if(!empty($tags)) {
            $str = str_replace('#','', $tweet);

            foreach ($tags as $tag) {
                $tag = str_replace('#', '', $tag);
                $str = str_replace($tag, "<a href=\"" . "hashtag?hashtag=" . $tag . "\">" . '#' . $tag . "</a>", $str);
            }

            $text = $str;
        }

        return $text;
    }

    public static function getTweetForRetweet($tweet_id) {
        return DB::table('tweets')
                    ->leftJoin('users', 'users.id', '=', 'tweets.idCreator')
                    ->where('tweets.id', $tweet_id)
                    ->get();
    }
}