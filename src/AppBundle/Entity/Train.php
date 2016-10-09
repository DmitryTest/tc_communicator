<?php

namespace AppBundle\Entity;

use DateTime;

class Train {
    /** @var int */
    public $number;

    /** @var TrainCategory */
    public $category;

    /** @var DateTime */
    public $departTime;

    /** @var DateTime */
    public $arrivalTime;

    /** @var int */
    public $travelTime;

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
     * @return TrainCategory
     */
    public function getCategory(): TrainCategory
    {
        return $this->category;
    }

    /**
     * @param TrainCategory $category
     */
    public function setCategory(TrainCategory $category)
    {
        $this->category = $category;
    }

    /**
     * @return DateTime
     */
    public function getDepartTime(): DateTime
    {
        return $this->departTime;
    }

    /**
     * @param DateTime $departTime
     */
    public function setDepartTime(DateTime $departTime)
    {
        $this->departTime = $departTime;
    }

    /**
     * @return DateTime
     */
    public function getArrivalTime(): DateTime
    {
        return $this->arrivalTime;
    }

    /**
     * @param DateTime $arrivalTime
     */
    public function setArrivalTime(DateTime $arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;
    }

    /**
     * @return int
     */
    public function getTravelTime(): int
    {
        return $this->travelTime;
    }

    /**
     * @param int $travelTime
     */
    public function setTravelTime(int $travelTime)
    {
        $this->travelTime = $travelTime;
    }
}