<?php

namespace AppBundle\Service\Rzd;

use SimpleXMLElement;

class Api {
    const BASE_PATH = 'https://raw.githubusercontent.com/DmitryTest/tc_communicator/master/web/ws/';
    const TRAINS_PATH = 'trains_list.xml';
    const CARRIAGE_PATH = 'carriages_list.xml';
    const ERROR_PATH = 'error.xml';

    /**
     * @param string $path
     * @return SimpleXMLElement
     */
    public function getXml(string $path)
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
