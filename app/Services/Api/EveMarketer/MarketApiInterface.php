<?php

namespace App\Services\Api\EveMarketer;

interface MarketApiInterface
{
    /**
     * @param int[] $types
     * @return AppraisalItem[]
     */
    public function getMarketPrices(array $types): array;
}