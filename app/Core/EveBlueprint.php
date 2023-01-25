<?php

namespace App\Core;

use App\Core\EveMarketApi\EveMarketer;
use App\Core\Exceptions\EveBlueprintException;
use App\Models\InvType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EveBlueprint
{
    public static bool $useCache = true;

    public static int $ttlCacheHours = 12;

    public function getTypeId(string $blueprintName): ?int
    {
        $result = DB::table('invTypes')
            ->where(['typeName' => $blueprintName])
            ->first(['typeID']);
        return $result ? $result->typeID : null;
    }

    public function appraisal(int $typeId)
    {
        $result = self::$useCache ?
            Cache::get(self::getAppraisalCacheKey($typeId)) : null;

        if ($result === null) {
            $item = InvType::find($typeId);

            $materials = $item->materials;
            if (empty($materials)) {
                throw new EveBlueprintException('no manufacture materials found');
            }

            $products = $item->products;
            if (empty($products)) {
                throw new EveBlueprintException('no manufacture products found');
            }

            // get prices for products and materials in one request
            $prices = (new EveMarketer())->getMarketPrices(
                array_merge(
                    array_column($products, 'typeId'),
                    array_column($materials, 'typeId')
                )
            );

            foreach ($materials as $material) {
                $key = array_search(
                    $material->typeId,
                    array_column($prices, 'typeId')
                );
                $material->sellPrice = $prices[$key]['sellPrice'];
            }

            foreach ($products as $product) {
                $key = array_search(
                    $product->typeId,
                    array_column($prices, 'typeId')
                );
                $product->sellPrice = $prices[$key]['sellPrice'];
            }

            $result = [
                'materials' => $materials,
                'products' => $products,
            ];

            if (self::$useCache) {
                Cache::put(
                    self::getAppraisalCacheKey($typeId),
                    $result,
                    now()->addHours(self::$ttlCacheHours)
                );
            }
        }

        return $result;
    }

    protected static function getAppraisalCacheKey(int $typeId): string
    {
        return implode('_', [
            'EveBlueprint',
            'Appraisal',
            $typeId
        ]);
    }
}
