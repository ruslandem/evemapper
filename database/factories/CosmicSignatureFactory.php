<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CosmicSignatureFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'characterId' => 1,
            'solarSystemName' => 'Jita',
            'signatureId' => $faker->regexify('[A-Z]{3}-[0-9]{3}'),
            'signatureName' => $faker->randomElement([
                'Regional Guristas Command Center',
                'Blood Surveillance Squad',
                'Serpentis Owned Station',
                'Guardian Angels Surveillance Squad',
                'Unidentified wormhole',
                ''
            ]),
            'groupName' => $faker->randomElement([
                'Data Site',
                'Relic Site',
                'Combat Site',
                'Wormhole',
            ]),
            'created_at' => (new \DateTime)->format(\DateTime::ATOM),
            'updated_at' => (new \DateTime)->format(\DateTime::ATOM)
        ];
    }
}
