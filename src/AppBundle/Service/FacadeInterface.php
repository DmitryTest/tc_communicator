<?php

namespace AppBundle\Service;

interface FacadeInterface {

    /**
     * @param Search $search
     * @return array
     */
    public function getTrainsList(Search $search);

    /**
     * @param Search $search
     * @return array
     */
    public function getCarriagesList(Search $search);

}
