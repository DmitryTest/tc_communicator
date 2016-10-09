<?php

namespace AppBundle\Service;

use AppBundle\Entity\Carriage;
use AppBundle\Entity\CarriageType;
use AppBundle\Entity\TrainCategory;
use AppBundle\Entity\Train;
use DateTime;
use SimpleXMLElement;

class RzdFacade {
    const BASE_PATH = '../web/ws/';
    const TRAINS_PATH = 'trains_list.xml';
    const CARRIAGE_PATH = 'carriages_list.xml';
    const ERROR_PATH = 'error.xml';

    private $errors = [];

    /**
     * @param Search $search
     * @return array
     */
    public function getTrainsList(Search $search)
    {
        return $this->getList(self::TRAINS_PATH, $search);
	}

    /**
     * @param Search $search
     * @return array
     */
    public function getCarriagesList(Search $search)
    {
        return $this->getList(self::CARRIAGE_PATH, $search);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $listName
     * @param Search $search
     * @return array
     */
    private function getList(string $listName, Search $search)
    {
        $rawData = $this->getXml($listName); // . $search->generateGetMethod()

        $error = $this->checkRawData($rawData, $listName);

        $list = [];
        if ($error) return $list;

        if ($listName == self::TRAINS_PATH) {
            $list = $this->normalizeTrains($rawData);
        } elseif ($listName == self::CARRIAGE_PATH) {
            $list = $this->normalizeCarriage($rawData);
        }
        return $list;
    }

    /**
     * @param SimpleXMLElement $rawData
     * @param string $listName
     * @return bool
     */
    private function checkRawData(SimpleXMLElement $rawData, string $listName)
    {
        $isError = (bool)$rawData->Error;
        if ($isError) {
            $this->errors[$listName]['code'] = $rawData->Code;
            $this->errors[$listName]['desc'] = $rawData->Descr;
        }
        return $isError;
    }

    /**
     * @param SimpleXMLElement $rawData
     * @return array
     */
	private function normalizeTrains(SimpleXMLElement $rawData)
    {
        $trains = [];
        foreach ($rawData->N as $trainData) {
            $time = explode(":", $trainData->T3);
            $travelTime = intval($time[0])*60 + intval($time[1]);
            $departTime = DateTime::createFromFormat('d.m H:i', (string)$trainData->D . ' ' . (string)$trainData->T1);
            $arrivalTime = DateTime::createFromFormat('d.m H:i', (string)$trainData->D1 . ' ' . (string)$trainData->T4);

            $categories = new TrainCategory();
            $categories->setCategory($trainData->KN);

            $train = new Train();
            $train->setNumber((int)$trainData->N1);
            $train->setCategory($categories);
            $train->setDepartTime($departTime);
            $train->setArrivalTime($arrivalTime);
            $train->setTravelTime($travelTime);
            $trains[] = $train;
        }

        return $trains;
    }

    /**
     * @param SimpleXMLElement $rawData
     * @return array
     */
    private function normalizeCarriage(SimpleXMLElement $rawData)
    {
        $carriages = [];
        foreach ($rawData->N->CV as $carriageData) {
            $type = new CarriageType();
            $type->setType($carriageData->KV);

            $carriage = new Carriage();
            $carriage->setNumber((int)$carriageData->VH);
            $carriage->setType($type);
            $carriage->setFreePlaces($carriageData->H);
            $carriage->setPrice((float)$carriageData->TF);
            $carriages[] = $carriage;
        }
        return $carriages;
    }

    /**
     * @param string $path
     * @return SimpleXMLElement
     */
	private function getXml(string $path)
    {
        $path = $this->generateError($path);
        $url = self::BASE_PATH . $path;
        $xml = simplexml_load_file($url) or die("feed not loading");
        return $xml;
    }

    /**
     * @param $path
     * @return string
     */
    private function generateError($path)
    {
        return rand(0, 4) == 4
            ? self::ERROR_PATH
            : $path;
    }
}
