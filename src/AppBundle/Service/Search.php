<?php

namespace AppBundle\Service;

use DateTime;

class Search {
    /** @var int */
    private $fromStation;

    /** @var int */
    private $toStation;

    /** @var DateTime */
    private $departDate;

    /** @var int */
    private $trainId;

    /**
     * @return int
     */
    public function getFromStation(): int
    {
        return $this->fromStation;
    }

    /**
     * @param int $fromStation
     */
    public function setFromStation(int $fromStation)
    {
        $this->fromStation = $fromStation;
    }

    /**
     * @return int
     */
    public function getToStation(): int
    {
        return $this->toStation;
    }

    /**
     * @param int $toStation
     */
    public function setToStation(int $toStation)
    {
        $this->toStation = $toStation;
    }

    /**
     * @return DateTime
     */
    public function getDepartDate(): DateTime
    {
        return $this->departDate;
    }

    /**
     * @param DateTime $departDate
     */
    public function setDepartDate(DateTime $departDate)
    {
        $this->departDate = $departDate;
    }

    /**
     * @return int
     */
    public function getTrainId(): int
    {
        return $this->trainId;
    }

    /**
     * @param int $trainId
     */
    public function setTrainId(int $trainId)
    {
        $this->trainId = $trainId;
    }

    /**
     * @return string
     */
    public function generateGetMethod()
    {
        $getMethod =
            '?from=' . $this->getFromStation() .
            '&to=' . $this->getToStation() .
            '&month=' . $this->getDepartDate()->format('m') .
            '&day=' . $this->getDepartDate()->format('d');

        if ($this->getTrainId()) {
            $getMethod .= '&train=' . $this->getTrainId();
        }

        return $getMethod;
    }
}
