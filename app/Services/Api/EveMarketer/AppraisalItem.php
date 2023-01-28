<?php

namespace App\Services\Api\EveMarket;

class AppraisalItem implements \JsonSerializable
{
    protected int $typeId;
    protected int $sellVolume;
    protected int $sellPrice;
    protected int $buyVolume;
    protected int $buyPrice;

    function __construct(?array $array)
    {
        if ($array) {
            $this->typeId = $array['typeId'];
            $this->sellVolume = $array['sellVolume'];
            $this->sellPrice = $array['sellPrice'];
            $this->buyVolume = $array['buyVolume'];
            $this->buyPrice = $array['buyPrice'];
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'typeId' => $this->typeId,
            'sellVolume' => $this->sellVolume,
            'sellPrice' => $this->sellPrice,
            'buyVolume' => $this->buyVolume,
            'buyPrice' => $this->buyPrice
        ];
    }

    /**
     * Get the value of typeId
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set the value of typeId
     *
     * @return  self
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get the value of sellVolume
     */
    public function getSellVolume()
    {
        return $this->sellVolume;
    }

    /**
     * Set the value of sellVolume
     *
     * @return  self
     */
    public function setSellVolume($sellVolume)
    {
        $this->sellVolume = $sellVolume;

        return $this;
    }

    /**
     * Get the value of sellPrice
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set the value of sellPrice
     *
     * @return  self
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get the value of buyVolume
     */
    public function getBuyVolume()
    {
        return $this->buyVolume;
    }

    /**
     * Set the value of buyVolume
     *
     * @return  self
     */
    public function setBuyVolume($buyVolume)
    {
        $this->buyVolume = $buyVolume;

        return $this;
    }

    /**
     * Get the value of buyPrice
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set the value of buyPrice
     *
     * @return  self
     */
    public function setBuyPrice($buyPrice)
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }
}
