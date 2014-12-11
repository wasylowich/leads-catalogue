<?php

use Faker\Factory as Faker;
use Models\Lead;
use Models\NewsletterSubscription;

class NewsletterSubscriptionsTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        foreach (Lead::all() as $lead) {
            if ($lead->newsletter) {
                NewsletterSubscription::create([
                    'lead_id' => $lead->id,
                    'format'  => $faker->randomElement(['text','html']),
                ]);
            }
        }
    }

}
