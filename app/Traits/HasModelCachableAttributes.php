<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait HasModelCachableAttributes
{
    protected function useAttributeCache(string $name, callable $func)
    {
        $value = $this->getCachedAttribute($name);

        return $value ? $value : $this->setCachedAttribute($name, $func());
    }

    /**
     * Return cached value of the attribute.
     * 
     * @param string $name
     * @return mixed|null
     */
    protected function getCachedAttribute(string $name)
    {
        $key = $this->getCachedAttributeCacheKey($name);

        return Cache::get($key);
    }

    /**
     * Save atribute value into cache.
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function setCachedAttribute(string $name, $value)
    {
        $key = $this->getCachedAttributeCacheKey($name);

        Cache::put($key, $value);

        return $value;
    }

    /**
     * Get unique key for the attribute.
     * 
     * @param string $name
     * @return string
     */
    private function getCachedAttributeCacheKey(string $name): string
    {
        if ($this instanceof Model) {
            return implode('_', [
                'attribute',
                $this->table ?? '',
                $this->primaryKey ?? '',
                $name
            ]);
        }

        return $name;
    }
}
