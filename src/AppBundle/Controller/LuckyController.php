<?php

// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{count}")
     * @throws \InvalidArgumentException
     */
    public function numberAction($count)
    {

        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        $numbersList = implode(' - ', $numbers);

        /*
        $html = $this->container->get('templating')->render(
            'lucky/number.html.twig',
            array('luckyNumberList' => $numbersList)
        );

        return new Response($html);
    */
        return $this->render(
            'lucky/number.html.twig',
            array('luckyNumberList' => $numbersList)
        );
    }

    /**
     * @Route("/api/lucky/number", name="lucky")
     * @throws \InvalidArgumentException
     */
    public function apiNumberAction()
    {
        $data = array(
            'lucky_number' => rand(0, 100),
        );


        return new JsonResponse($data);
    }
    /**
     * @Route("/test", name="test")
     * @throws \InvalidArgumentException
     */
    public function testAction()
    {
        return $this->render(
            'default/index.html.twig'
        );
    }
}