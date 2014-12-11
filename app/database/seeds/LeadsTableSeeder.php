<?php

use Faker\Factory as Faker;
use Models\Lead;

class LeadsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('da_DK');

		foreach(range(1, 10) as $index)
		{
			Lead::create([
                'name'        => $faker->name,
                'email'       => $faker->email,
                'phone'       => $faker->phoneNumber,
                'address'     => $faker->streetAddress,
                'postal_code' => $faker->postcode,
                'city'        => $faker->city,
                'newsletter'  => (bool) rand(0, 1),
			]);
		}
	}

}
