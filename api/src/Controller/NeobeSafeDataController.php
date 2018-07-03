<?php

namespace App\Controller;

use App\Entity\ApiResponse;
use App\Manager\PartnerManager;
use Doctrine\DBAL\Schema\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NeobeSafeDataController
 * @package App\Controller
 *
 */
class NeobeSafeDataController extends Controller
{

    /**
     * Get structure
     *
     * @Route(
     *     name="api_create_account",
     *     path="/create-account",
     *     methods={"POST"}
     * )
     */
    public function createAccount(Request $request)
    {
        $resp = $this->get(PartnerManager::SERVICE_NAME)->createPartner($request);
        return new JsonResponse($resp, Response::HTTP_OK);
    }


}
