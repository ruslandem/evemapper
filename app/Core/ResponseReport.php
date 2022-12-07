<?php

namespace App\Core;

class ResponseReport
{
    protected array $counters = [];

    protected array $attributes;


    public function __construct()
    {
        $this->attributes = [
            'error' => null
        ];
    }

    public function increment(string $counter, int $value = 1)
    {
        if (!isset($this->counters[$counter])) {
            $this->counters[$counter] = 0;
        }
        $this->counters[$counter] += $value;

        return $this;
    }

    public function decrement(string $counter, int $value = 1)
    {
        if (!isset($this->counters[$counter])) {
            $this->counters[$counter] = 0;
        }
        $this->counters[$counter] -= $value;

        return $this;
    }


    public function set(string $attribute, string $value)
    {
        if (!array_key_exists($attribute, $this->counters)) {
            $this->attributes[$attribute] = $value;
        }

        return $this;
    }

    public function output(): array
    {
        return array_merge(
            $this->attributes,
            $this->counters
        );
    }
}
