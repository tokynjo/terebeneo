<?php

namespace App\Controller;

use App\Entity\Constants\Constant;
use App\Manager\PartnerManager;
use App\Manager\ValidationLogManager;
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
     * @Route("/etape-1/{token}", defaults={"_format"="html","token"="0"}, methods={"GET"}, name="steps_one")
     * @param Request $request
     * @return Response
     */
    public function stepOneAction(Request $request, $token = null)
    {
        $partener = $this->get(PartnerManager::SERVICE_NAME)->etape1($token);
        return $this->render('front/steps/step1.html.twig', ['partener' => $partener]);
    }

    /**
     * second step
     *
     * @Route("/etape-2/{token}", defaults={"page": "1", "_format"="html","token"="0"}, methods={"GET","POST"}, name="steps_two")
     * @Route("/etape-2", defaults={"page": "1", "_format"="html"}, methods={"GET","POST"}, name="steps_two_")
     * @param Request $request
     * @return Response
     */
    public function stepTwoAction(Request $request, $token = null)
    {
        $this->get(PartnerManager::SERVICE_NAME)->etape2($request, $token);
        return $this->render('front/steps/step2.html.twig', ["token" => $token]);
    }

    /**
     * third step
     *
     * @Route("/etape-3/{token}", defaults={"page": "1", "_format"="html","token"="0"}, methods={"GET"}, name="steps_three")
     * @param Request $request
     * @return Response
     */
    public function stepThreeAction(Request $request, $token = null)
    {
        $partener = $this->get(PartnerManager::SERVICE_NAME)->findOneBy(["hash"=>($token?$token:"")]);
        if($partener) {
                $existLog = $this->get(ValidationLogManager::SERVICE_NAME)->findBy(
                    [
                        "etape" => Constant::STEP_THREE,
                        "partner" => $partener
                    ]
                );
                if (!$existLog) {
                    $validation = $this->get(ValidationLogManager::SERVICE_NAME)->createNew();
                    $validation->setPartner($partener);
                    $validation->setEtape(Constant::STEP_THREE);
                    $this->get(PartnerManager::SERVICE_NAME)->saveAndFlush($validation);
                }
        }
        return $this->render('front/steps/step3.html.twig', ['token' => $token]);
    }

}
