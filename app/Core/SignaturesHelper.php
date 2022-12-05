<?php

namespace App\Core;

class SignaturesHelper
{
    public static function decodeClipboardText(string $characterId, string $solarSystem, string $text): array
    {
        $signatures = [];

        $lines = explode(PHP_EOL, $text);
        foreach ($lines as $line) {

            list($id,, $group, $name) = str_getcsv(trim($line), "\t");

            if ($id) {
                $signatures[] = [
                    'characterId' => $characterId,
                    'solarSystemName' => $solarSystem,
                    'signatureId' => $id,
                    'signatureName' => $name,
                    'groupName' => $group ?? '',
                ];
            }
        }

        return $signatures;
    }
}
