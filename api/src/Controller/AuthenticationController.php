<?php

namespace App\Controller;

use App\Entity\ApiResponse;
use App\Entity\Constants\Constant;
use App\Manager\EmailAutomatiqueManager;
use App\Services\ApiRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends Controller
{

    public function __construct()
    {

    }

    /**
     * @Route(
     *     name="get_token",
     *     path="/get-token",
     *     methods={"POST"}
     * )
     */
    public function getToken(Request $request)
    {
        $response = new ApiResponse();
        if($request->getContent()) {
            $data = json_decode($request->getContent());
            $userName = $data->username;
            $password = $data->password;
        }else{
            $userName = $request->get("username");
            $password = $request->get("password");
        }
        $userByName = $this->getDoctrine()->getRepository('App:User')->findOneBy(
            ['username' => $userName]
        );
        $userByName = $userByName->hasRole('ROLE_API') ? $userByName : null;
        $userByEmail = $this->getDoctrine()->getRepository('App:User')->findOneBy(
            ['email' => $userName]
        );
        $userByEmail = $userByEmail->hasRole('ROLE_API') ? $userByEmail : null;

        $user = ($userByEmail)?$userByEmail:$userByName;
        if (!$user) {
            $response->setCode(Response::HTTP_NOT_FOUND)
                ->setMessage("user not found");
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);
        if (!$isValid) {
            $response->setCode(Response::HTTP_UNAUTHORIZED)
                ->setMessage("Password not valid");
            return new JsonResponse($response, Response::HTTP_UNAUTHORIZED);
        }
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        $response->setCode(Response::HTTP_OK)
            ->setMessage("Success")
            ->setData(['token' => $jwtManager->create($user)]);
        return new JsonResponse($response);
    }
}
