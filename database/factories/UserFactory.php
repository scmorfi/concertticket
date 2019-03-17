<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use \Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Concert::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'subtitle' => $faker->name,
        "date" => Carbon::parse('+2 weeks'),
        "ticket_price" => rand(10000,100000),
        'venue' => $faker->name,
        'venue_address' => $faker->name,
        'city' => $faker->name,
        'state' => 'ON',
        'zip' => rand(10000,100000),
        'total_tickets_available' => 20,
        'additional_information' => 'Lorem ipsum dolor sit amet, sea et partem mandamus necessitatibus. Cum ut aeque minimum interesset. Sit persecuti expetendis ut, ius nostro similique consectetuer ea, ponderum perfecto vim an. Partem perfecto urbanitas ea duo, ea agam solum dicit pro, ridens offendit abhorreant in mei.',
    ];
});
$factory->state(App\Concert::class,"publiched" ,function(Faker $faker){
    return [
        "publiched_at" => Carbon::parse('+1 weeks')
    ];
});
$factory->state(App\Concert::class,"unpubliched" ,function(Faker $faker){
    return [
        "publiched_at" => null
    ];
});
