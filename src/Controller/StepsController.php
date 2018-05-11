<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StepsController extends Controller
{

    /**
     * First step
     *
     * @Route("/", defaults={"_format"="html"}, methods={"GET"}, name="steps_first")
     * @Route("/etape-1", defaults={"_format"="html"}, methods={"GET"}, name="steps_one")
     * @param Request $request
     * @return Response
     */
    public function stepOneAction(Request $request)
    {
        return $this->render('front/steps/step1.html.twig', ['post' => 'aa']);
    }

    /**
     * second step
     *
     * @Route("/etape-2", defaults={"page": "1", "_format"="html"}, methods={"GET","POST"}, name="steps_two")
     * @param Request $request
     * @return Response
     */
    public function stepTwoAction(Request $request)
    {
        return $this->render('front/steps/step2.html.twig', ['post' => 'aa']);
    }

    /**
     * third step
     *
     * @Route("/etape-3", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="steps_three")
     * @param Request $request
     * @return Response
     */
    public function stepThreeAction(Request $request)
    {
        return $this->render('front/steps/step3.html.twig', ['post' => 'aa']);
    }

}
