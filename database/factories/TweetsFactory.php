<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TweetsFactory extends Factory
{
    public function definition()
    {
        return [
            'idCreator' => Str::random(10),
            'tweetText' => fake()->text(),
            'tweetDateCreate' => 'CURRENT_TIMESTAMP' 
        ];
    }
}
