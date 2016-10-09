<?php

namespace AppBundle\Entity;

class Carriage {
    /** @var int $number */
    public $number;

    /** @var CarriageType */
    public $type;

    /** @var  string $freePlaces */
    public $freePlaces;

    /** @var float $price */
    public $price;

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return CarriageType
     */
    public function getType(): CarriageType
    {
        return $this->type;
    }

    /**
     * @param CarriageType $type
     */
    public function setType(CarriageType $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFreePlaces(): string
    {
        return $this->freePlaces;
    }

    /**
     * @param string $freePlaces
     */
    public function setFreePlaces(string $freePlaces)
    {
        $this->freePlaces = $freePlaces;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }
}
