<?php

namespace App\Core;

class SignaturesHelper
{
    /**
     * Transforms clipboard data (copied from the Eve Online client) to an array.
     * 
     * @param string $characterId
     * @param string $solarSystem
     * @param string $text
     * @return array
     */
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

/*
Sample clipboard text:

ISN-720	Cosmic Signature			0.0%	2.58 AU
KOL-024	Cosmic Signature			0.0%	3.73 AU
QPI-926	Cosmic Signature			0.0%	6.78 AU
ROJ-096	Cosmic Signature	Regional Guristas Command Center	Data Site   0.0%	29.53 AU
XCO-255	Cosmic Signature			0.0%	1.79 AU
KOL-024	Cosmic Signature	Regional Guristas Command Center	Data Site	0.0%	1.79 AU
*/