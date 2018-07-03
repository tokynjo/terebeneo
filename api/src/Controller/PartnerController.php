<?php

namespace App\Controller;

use App\Entity\ApiResponse;
use App\Entity\Constants\Constant;
use App\Services\ApiRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends Controller
{
    /**
     * @Route(
     *     name="get_partner",
     *     path="/get-partner",
     *     methods={"POST"}
     * )
     */
    public function getPartner(Request $request)
    {
        die('fddfdsf');
        $response = new ApiResponse();
        $apiRequest = new ApiRequest();

        return new JsonResponse($response);
    }
}
