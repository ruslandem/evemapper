<?php

namespace App\Core\EveMarketApi;

interface MarketApiInterface
{
    /**
     * @param int[] $types
     * @return AppraisalItem[]
     */
    public function getMarketPrices(array $types): array;
}