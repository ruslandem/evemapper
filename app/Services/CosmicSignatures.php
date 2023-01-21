<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\ResponseReport;
use App\Models\Signature;
use App\Services\CosmicSignatureUpsertStatus;
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

        if (count($signatures) == 0) {
            return $report
                ->set('error', 'no signatures found');
        }

        // deleting absent signatures
        if (count($signatures) > 0 && $replace) {
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

            match ($upserted) {
                CosmicSignatureUpsertStatus::Created => $report->increment('created'),
                CosmicSignatureUpsertStatus::Updated => $report->increment('updated')
            };
        }

        return $report;
    }

    /**
     * Insert or update signature with the specified signatureId.
     * 
     * @param array $signature
     * @return CosmicSignatureUpsertStatus
     */
    public function upsertSignature(array $signature): CosmicSignatureUpsertStatus
    {
        $validator = Validator::make($signature, [
            'characterId' => 'required|integer',
            'solarSystemName' => 'required|string',
            'signatureId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return CosmicSignatureUpsertStatus::Unchanged;
        }

        $found = Signature::where([
            'characterId' => $signature['characterId'],
            'solarSystemName' => $signature['solarSystemName'],
            'signatureId' => $signature['signatureId']
        ])->first();

        if ($found) {
            $found->updateData($signature)->save();
            return CosmicSignatureUpsertStatus::Updated;
        }

        Signature::create($signature);
        return CosmicSignatureUpsertStatus::Created;
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

        if ($lines !== false) {
            foreach ($lines as $line) {
                $items = str_getcsv(trim($line), "\t");
                if (count($items) >= 4) {
                    list($id,, $group, $name) =  $items;

                    if (!empty($id)) {
                        $signatures[] = [
                            'characterId' => $characterId,
                            'solarSystemName' => $solarSystem,
                            'signatureId' => $id,
                            'signatureName' => $name,
                            'groupName' => $group ?? '',
                        ];
                    }
                }
            }
        }

        return $signatures;
    }
}
