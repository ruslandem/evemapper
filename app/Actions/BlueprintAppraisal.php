<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\InvType;
use App\Services\Api\MarketApiInterface;
use Illuminate\Support\Facades\Cache;

class BlueprintAppraisal
{
    public function __construct(
        protected MarketApiInterface $marketApi,
        protected readonly bool $useCache = true,
        protected readonly string $priceType = 'sellPrice'
    ) {
    }

    public function appraise(int $blueprintTypeId)
    {
        if ($this->useCache) {
            $cached = Cache::get(__CLASS__ . $blueprintTypeId);

            if ($cached) {
                return $cached;
            }
        }

        $item = InvType::with(['materials', 'products'])->find($blueprintTypeId);

        if (!$item) {
            throw new \RuntimeException('blueprint not found');
        }

        $prices = $this->getPrices($item);

        $result = $item->toArray();

        /**
         * calc materials costs
         */
        foreach ($result['materials'] as &$value) {
            $value['price'] = $prices[$value['materialTypeID']][$this->priceType];
        }

        /**
         * calc products costs
         */
        foreach ($result['products'] as &$value) {
            $value['price'] = $prices[$value['productTypeID']][$this->priceType];
        }

        /**
         * calc totals
         */
        $result['totals'] = [
            'materialsCosts' => $this->getTotalCost($result['materials']),
            'productsCosts' => $this->getTotalCost($result['products']),
        ];

        if ($this->useCache) {
            Cache::set(__CLASS__ . $blueprintTypeId, $result);
        }

        return $result;
    }


    protected function getTotalCost(array $items): int
    {
        return array_sum(
            array_map(
                fn ($value) => $value['quantity'] * $value['price'],
                $items
            )
        );
    }

    protected function getPrices(InvType $item)
    {
        if ($item->materials->count() < 1) {
            throw new \RuntimeException('blueprint materials not found');
        }

        if ($item->products->count() < 1) {
            throw new \RuntimeException('blueprint products not found');
        }

        $result = $this->marketApi->getMarketPrices(
            array_merge(
                $item->materials->pluck('materialTypeID')->toArray(),
                $item->products->pluck('productTypeID')->toArray()
            )
        );

        return array_combine(
            array_column($result, 'typeId'),
            $result
        );
    }
}
