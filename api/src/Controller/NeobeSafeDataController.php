<?php

namespace App\Controller;

use App\Entity\Api\ApiResponse;
use App\Manager\FileManager;
use App\Manager\FileUserManager;
use App\Manager\FolderManager;
use App\Manager\FolderUserManager;
use App\Manager\ViewManager;
use Doctrine\DBAL\Schema\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $resp = new ApiResponse();


        return new JsonResponse($resp, Response::HTTP_OK);
    }


}
