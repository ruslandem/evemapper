<?php

namespace App\Services\Api\EveMarket;

interface MarketApiInterface
{
    /**
     * @param int[] $types
     * @return AppraisalItem[]
     */
    public function getMarketPrices(array $types): array;
}