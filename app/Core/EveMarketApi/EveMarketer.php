<?php

namespace App\Core\EveMarketApi;

use App\Exceptions\EveApiException;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class EveMarketer implements MarketApiInterface
{
    public static string $url = 'https://api.evemarketer.com/ec/marketstat/json';

    public static int $useRegion = 10000002; // The Forge
    public static int $useSystem = 30000142; // Jita

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
            'regionlimit' => self::$useRegion,
            'usesystem' => self::$useSystem,
            'typeid' => implode(',', $types)
        ];

        $requestUrl = self::$url . '?' . http_build_query($queryData);

        if (Config::get('app.api.log', true)) {
            Log::channel('api')->info($requestUrl);
        }
        
        $response = (new HttpClient())->request('GET', $requestUrl);

        if ($response->getStatusCode() !== 200) {
            throw new EveApiException("EveMarketer API request failed with code {$response->getStatusCode()}");
        }

        $decodedJson = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $appraisalItems = [];
        foreach ($decodedJson as $item) {
            $appraisalItems[] = (new AppraisalItem([
                'typeId' => $item['buy']['forQuery']['types'][0],
                'sellVolume' => $item['sell']['volume'] ?? 0,
                'sellPrice' => $item['sell']['min'] ?? 0,
                'buyVolume' => $item['buy']['volume'] ?? 0,
                'buyPrice' => $item['buy']['median'] ?? 0,
            ]))->toArray();
        }

        return $appraisalItems;
    }
}
