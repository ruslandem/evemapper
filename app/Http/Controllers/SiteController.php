<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SecuritySpace;
use App\Models\RatsDamage;
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

        return response()->json($elements);
    }

    public function getRatsDamages()
    {
        return response()->json(RatsDamage::all()->toArray());
    }
}
