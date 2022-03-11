<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\MorphAddress\Models\Entities\Address;
use WalkerChiu\MorphAddress\Models\Entities\AddressLang;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'type'       => $faker->randomElement(config('wk-core.class.morph-address.addressType')::getCodes()),
        'phone'      => $faker->phoneNumber,
        'email'      => $faker->email,
        'area'       => $faker->randomElement(config('wk-core.class.core.countryZone')::getCodes())
    ];
});

$factory->define(AddressLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'address_line1', 'address_line2']),
        'value' => $faker->sentence
    ];
});
