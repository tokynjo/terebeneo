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
     * Create account
     *
     * @Route(
     *     name="api_create_account",
     *     path="/api/create-account",
     *     methods={"POST"}
     * )
     */
    public function createAccount(Request $request)
    {
        $resp = $this->get(PartnerManager::SERVICE_NAME)->createPartner($request);
        return new JsonResponse($resp, Response::HTTP_OK);
    }

    /**
     * get list of clients created by a partner
     * Clients with account neobe or no
     *
     * @Route(
     *     name="api_partner_clients",
     *     path="/api/partner-clients/{id}",
     *     methods={"POST"}
     * )
     */
    public function getPartnerClients(Request $request)
    {
        $resp = $this->get(PartnerManager::SERVICE_NAME)->getPartnerClients($request);
        return new JsonResponse($resp, Response::HTTP_OK);
    }
}