<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    $startAt = $faker->dateTimeBetween('+1 days', '+10 days');
    $startAt->setTime(10, 0 , 0);
    $endAt = clone $startAt;
    $endAt->setTime(11, 0 , 0);
    return [
        'name' => $faker->name,
        'coach_name' => $faker->name,
        'capacity' => $faker->randomNumber(2),
        'start_at' => $faker->dateTime,
        'end_at' => $faker->dateTime,
    ];
});

$factory->state(Lesson::class, 'past', function (Faker $faker) {
    $startAt = $faker->dateTimeBetween('-1 days', '-10 days');
    $startAt->setTime(10, 0 , 0);
    $endAt = clone $startAt;
    $endAt->setTime(11, 0 , 0);

    return [
        'start_at' => $startAt->format('Y-m-d H:i:d'),
        'end_at' => $endAt->format('Y-m-d H:i:d')
    ];
});
