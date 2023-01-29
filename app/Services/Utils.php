<?php

namespace App\Services;


final class Utils
{
    /**
     * Substitute $array values with mapped values in $token.
     * 
     * @param array $array
     * @param array $tokens
     * @return array
     */
    public static function mapArray(array $array, array $tokens): array
    {
        $result = [];

        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $result[$key] = $tokens[$value] ?? null;
            }
        }

        return $result;
    }

    public static function searchAssocArray(array $array, string $keyName, $keyValue)
    {
        $key = array_search($keyValue, array_column($array, $keyName));
        return $key !== false ? $array[$key] : null;
    }
}
