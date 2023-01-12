<?php

namespace App\Core\EveMarketApi;

use App\Core\Exceptions\EveApiException;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as HttpClient;

class EveMarketer implements MarketApiInterface
{
    public static string $url = 'https://api.evemarketer.com/ec/marketstat/json';

    public static int $useSystem = 30000142;

    public function getMarketPrices(array $types): array
    {
        try {
            Validator::validate($types, [
                '*' => 'integer'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new EveApiException("invalid type for getting market price ({$e->getCode()})");
        }

        $queryData = [
            'usesystem' => self::$useSystem,
            'typeid' => implode(',', $types)
        ];

        $requestUrl = self::$url . '?' . http_build_query($queryData);

        $response = (new HttpClient())->request('GET', $requestUrl);

        if ($response->getStatusCode() !== 200) {
            throw new EveApiException("EveMarketer API request failed with code {$response->getStatusCode()}");
        }

        $decodedJson = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $appraisalItems = [];
        foreach ($decodedJson as $item) {
            $appraisalItems[] = new AppraisalItem([
                'typeId' => $item['buy']['forQuery']['types'][0],
                'sellVolume' => $item['sell']['volume'] ?? 0,
                'sellPrice' => $item['sell']['median'] ?? 0,
                'buyVolume' => $item['buy']['volume'] ?? 0,
                'buyPrice' => $item['buy']['median'] ?? 0,
            ]);
        }

        return $appraisalItems;
    }
}
