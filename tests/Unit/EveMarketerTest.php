<?php

namespace Tests\Unit;

use App\Core\EveMarketApi\EveMarketer;
use App\Core\Exceptions\EveApiException;
use Tests\TestCase;

class EveMarketerTest extends TestCase
{
    public function test_invalid_types()
    {
        $api = new EveMarketer();

        $this->expectException(EveApiException::class);
        $api->getMarketPrices([1, 2, 'abc', 4, 5]);

        $this->expectException(EveApiException::class);
        $api->getMarketPrices([1, 2, 6.3, 4, 5]);

        $this->expectException(EveApiException::class);
        $api->getMarketPrices([1, 2, 0xFF, 4, 5]);
    }
}
