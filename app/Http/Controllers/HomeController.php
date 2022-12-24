<?php

namespace App\Http\Controllers;

use App\Core\EveWormholeClasses;

class HomeController extends Controller
{
    public function getWormholeClasses()
    {
        $eveWormholeClasses = new EveWormholeClasses();

        $types = [
            'High' => 7,
            'Low' => 8,
            'Null' => 9,
            'C1' => 1,
            'C2' => 2,
            'C3' => 3,
            'C4' => 4,
            'C5' => 5,
            'C6' => 6,
            'C13' => 13,
            'Thera' => 12
        ];

        $colors = [
            'High' => '#8afb66',
            'Low' => '#f3ad2e',
            'Null' => '#f35757',
            'C1' => '#9df2fb',
            'C2' => '#9df2fb',
            'C3' => '#9df2fb',
            'C4' => '#9df2fb',
            'C5' => '#9df2fb',
            'C6' => '#9df2fb',
            'C13' => '#9df2fb',
            'Thera' => '#df9fdf'
        ];

        return array_map(function ($key, $value) use ($eveWormholeClasses, $colors) {
            return array(
                'name' => $key,
                'classes'  => $eveWormholeClasses->getList($value),
                'highlightColor' => $colors[$key]
            );
        }, array_keys($types), $types);
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