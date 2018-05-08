<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StepsController extends Controller
{

    /**
     * First steps
     *
     * @Route("/first", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="steps_first")
     * @param Request $request
     * @return Response
     */
    public function stepOneAction(Request $request)
    {
        return $this->render('front/steps/step1.html.twig', ['post' => 'aa']);
    }

    /**
     * First steps
     *
     * @Route("/first", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="steps_first")
     * @param Request $request
     * @return Response
     */
    public function stepTwoAction(Request $request)
    {
        return $this->render('front/steps/step2.html.twig', ['post' => 'aa']);
    }

}
