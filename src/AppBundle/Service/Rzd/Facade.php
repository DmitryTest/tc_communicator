<?php

namespace AppBundle\Service\Rzd;

use AppBundle\Entity\Carriage;
use AppBundle\Entity\CarriageType;
use AppBundle\Entity\TrainCategory;
use AppBundle\Entity\Train;
use AppBundle\Service\FacadeInterface;
use AppBundle\Service\Search;
use DateTime;
use SimpleXMLElement;

class Facade implements FacadeInterface {
    private $errors = [];

    /** @var Api $api */
    private $api;

    /**
     * RzdFacade constructor.
     */
    public function __construct()
    {
        $this->api = new Api();
    }

    /**
     * @param Search $search
     * @return array
     */
    public function getTrainsList(Search $search)
    {
        return $this->getList(Api::TRAINS_PATH, $search->generateTrainGetMethod());
	}

    /**
     * @param Search $search
     * @return array
     */
    public function getCarriagesList(Search $search)
    {
        return $this->getList(Api::CARRIAGE_PATH, $search->generateCarriageGetMethod());
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
     * @param string $path
     * @return array
     */
    private function getList(string $listName, string $path)
    {
        $rawData = $this->api->getXml($listName . $path);

        $error = $this->checkRawData($rawData, $listName);

        $list = [];
        if ($error) return $list;

        if ($listName == Api::TRAINS_PATH) {
            $list = $this->normalizeTrains($rawData);
        } elseif ($listName == Api::CARRIAGE_PATH) {
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
}
