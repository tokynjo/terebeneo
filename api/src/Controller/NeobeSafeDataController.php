<?php

namespace App\Controller;

use App\Entity\Api\ApiResponse;
use App\Entity\Constants\Constant;
use App\Manager\FileManager;
use App\Manager\FileUserManager;
use App\Manager\FolderManager;
use App\Manager\FolderUserManager;
use App\Manager\ViewManager;
use App\Services\ApiRequest;
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
     *     name="api_get_structure",
     *     path="/api/get-structure",
     *     methods={"POST"}
     * )
     */
    public function getStructure(Request $request)
    {
        $resp = new ApiResponse();


        return new JsonResponse($resp, Response::HTTP_OK);
    }


}
