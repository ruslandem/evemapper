<?php

namespace App\Services;

use App\Core\ResponseReport;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CosmicSignatures
{
    public const SIG_UNCHAGED = 0;
    public const SIG_UPDATED = 1;
    public const SIG_CREATED = 2;

    public function updateFromClipboardText(int $characterId, string $solarSystem, string $text, bool $replace = false): ResponseReport
    {
        $report = new ResponseReport();

        // parsing text
        $signatures = $this->getArrayFromClipboardText($characterId, $solarSystem, $text);

        // deleting absent signatures
        if ($replace) {
            $removed = $this->removeAbsentSignatures(
                $characterId,
                $solarSystem,
                $signatures
            );

            $report->increment('deleted', $removed);
        }

        // updating/inserting signatures
        foreach ($signatures as $signature) {
            $upserted = $this->upsertSignature($signature);

            switch ($upserted) {
                case self::SIG_CREATED:
                    $report->increment('created');
                    break;
                case self::SIG_UPDATED:
                    $report->increment('updated');
                    break;
                case self::SIG_UNCHAGED:
                    // skip invalid signature record
                    break;
            }
        }

        return $report;
    }

    /**
     * Insert or update signature with the specified signatureId.
     * 
     * @param array $signature
     * @return int return either SIG_UPDATED, SIG_CREATED, or SIG_UNCHAGED
     */
    public function upsertSignature(array $signature): int
    {
        $validator = Validator::make($signature, [
            'characterId' => 'required|integer',
            'solarSystemName' => 'required|string',
            'signatureId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::SIG_UNCHAGED;
        }



        $found = Signature::where([
            'characterId' => $signature['characterId'],
            'solarSystemName' => $signature['solarSystemName'],
            'signatureId' => $signature['signatureId']
        ])->first();

        if ($found) {
            $found->updateData($signature)->save();
            return self::SIG_UPDATED;
        }

        Signature::create($signature);
        return self::SIG_CREATED;
    }

    public function removeAbsentSignatures(int $characterId, string $solarSystem, array $signatures)
    {
        $ids = array_column($signatures, 'signatureId');

        return Signature::where([
            'characterId' => $characterId,
            'solarSystemName' => $solarSystem,
        ])
            ->whereNotIn('signatureId', $ids)
            ->delete();
    }

    public function deleteSignature(int $characterId, string $solarSystem, string $signatureId): int
    {
        return Signature::where([
            'characterId' => $characterId,
            'solarSystemName' => $solarSystem,
            'signatureId' => $signatureId,
        ])->delete();
    }

    protected function getArrayFromClipboardText(int $characterId, string $solarSystem, string $text): array
    {
        $signatures = [];

        $lines = explode(PHP_EOL, trim($text));
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
