<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\EveWormholeClasses;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use TimeHunter\LaravelGoogleReCaptchaV3\Facades\GoogleReCaptchaV3;

class SiteController extends Controller
{
    public function getWormholeClasses()
    {
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

        return array_map(function ($key, $value) use ($colors) {
            return array(
                'name' => $key,
                'classes'  => EveWormholeClasses::getList($value),
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

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required',
            'email' => 'email|required',
            'message' => 'string|required',
            'gRecaptchaResponse' => 'string|required'
        ]);

        $captcha = GoogleReCaptchaV3::verifyResponse(
            $validated['gRecaptchaResponse'],
            $request->getClientIp()
        );

        if (!$captcha->isSuccess()) {
            $error = $captcha->getMessage();
            return response()->json([
                'message' => "Invalid captcha ({$error})"
            ], 400);
        }
        $validated['captchaScore'] = $captcha->getScore();

        $recipient = Config::get('mail.admin');

        if ($recipient) {
            Mail::to($recipient)->send(
                new ContactMessage($validated)
            );
        }

        return response()->json([
            'message' => 'Message sent'
        ]);
    }
}
