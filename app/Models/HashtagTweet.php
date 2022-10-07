<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HashtagTweet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'hashtag_tweet';
}
