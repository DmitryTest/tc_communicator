<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Search;
use AppBundle\Service\RzdFacade;
use DateTime;

class DefaultController extends Controller
{
    /**
     * @route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createSearchForm();
        $form->handleRequest($request);

        $trains = [];
        $carriages = [];
        $errors = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $search = $this->getSearch($data);

            $rzd = new RzdFacade();
            $trains = $rzd->getTrainsList($search);

            $trainId = $data['trainId'];
            if ($trainId) {
                $carriages = $rzd->getCarriagesList($search);
            }
            $errors = $rzd->getErrors();
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'trains' => $trains,
            'carriages' => $carriages,
            'errors' => $errors
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createSearchForm()
    {
        return $this->createFormBuilder()
            ->add('fromStation', ChoiceType::class, ['choices' => ['Kiev' => 2000000, 'Lvov' => 2000100]])
            ->add('toStation', ChoiceType::class, ['choices' => ['Odessa' => 2024120]])
            ->add('departDate', TextType::class, ['data' => '30.08'])
            ->add('trainId', HiddenType::class)
            ->add('save', SubmitType::class, ['label' => 'Get trains'])
            ->getForm();
    }

    /**
     * @param $data
     * @return Search
     */
    private function getSearch($data)
    {
        $search = new Search();
        $search->setFromStation($data['fromStation']);
        $search->setToStation($data['toStation']);
        $search->setDepartDate(DateTime::createFromFormat('d.m', $data['departDate']));
        $search->setTrainId((int)$data['trainId']);
        return $search;
    }
}
