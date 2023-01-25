<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SecuritySpace;
use App\Services\Wormholes;

class SiteController extends Controller
{
    public function getWormholeClasses()
    {
        /**
         * Describe security space items from 0 to 9
         */
        $elements = array_map(
            function (SecuritySpace $value) {
                return [
                    'name' => $value->name(),
                    'classes' => Wormholes::getWormholeClasses($value),
                    'highlightColor' => $value->highlightColor()
                ];
            },
            array_slice(SecuritySpace::cases(), 0, 10)
        );
        /**
         * Add description for other security space items (10..)
         */
        $otherTypes = [];
        foreach (array_slice(SecuritySpace::cases(), 10) as $value) {
            array_push($otherTypes, ...Wormholes::getWormholeClasses($value));
        }
        array_push($elements, [
            'name' => 'Other',
            'classes' => $otherTypes,
            'highlightColor' => SecuritySpace::Triglavian->highlightColor()
        ]);

        return $elements;
    }

    public function getRatsDamages()
    {
        return [
            [
                "race" => "Blood Raider",
                "damageToGet" => "EM/Thermal",
                "damageToUse" => "EM"
            ],
            [
                "race" => "Guristas",
                "damageToGet" => "Kinetic/Thermal",
                "damageToUse" => "Kinetic"
            ],
            [
                "race" => "Sanshas",
                "damageToGet" => "EM/Thermal",
                "damageToUse" => "EM"
            ],
            [
                "race" => "Serpentis",
                "damageToGet" => "Thermal/Kinetic",
                "damageToUse" => "Kinetic"
            ],
            [
                "race" => "Angel",
                "damageToGet" => "Explosive/Kinetic",
                "damageToUse" => "Explosive"
            ],
            [
                "race" => "Mordus",
                "damageToGet" => "Kinetic/Thermal",
                "damageToUse" => "Kinetic"
            ],
            [
                "race" => "Rogue Drones",
                "damageToGet" => "Explosive/Kinetic",
                "damageToUse" => "EM"
            ],
            [
                "race" => "Mercenary",
                "damageToGet" => "EM/Thermal",
                "damageToUse" => "Thermal"
            ],
            [
                "race" => "Minmatar",
                "damageToGet" => "Thermal",
                "damageToUse" => " Explo"
            ],
            [
                "race" => "Gallente",
                "damageToGet" => "Kinetic/Thermal",
                "damageToUse" => "Thermal"
            ],
            [
                "race" => "Amarr",
                "damageToGet" => "EM/Thermal",
                "damageToUse" => "Thermal"
            ],
            [
                "race" => "Caldari",
                "damageToGet" => "Kinetic/Thermal",
                "damageToUse" => "Kinetic"
            ],
        ];
    }
}
