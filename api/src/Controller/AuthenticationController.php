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

    /**
     * @Route(
     *     name="api_send_reset_user",
     *     path="/reset/password/send-mail",
     *     methods={"POST"},
     * )
     */
    public function resetPasswordRequest(Request $request)
    {
        $resp = new ApiResponse();
        try {
            $apiRequest = new ApiRequest();
        } catch (\Exception $e) {
            return new JsonResponse($resp->setMessage($e->getMessage())
                ->setCode(Response::HTTP_BAD_REQUEST));
        }
        $email = $apiRequest->getBodyRawParam('email');

        if (!$email) {
            return new JsonResponse($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing parameters.'));
        }
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);
        if (null === $user) {
            return new JsonResponse($resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('User not found.'));
        }
        $token = rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
        $user->setConfirmationToken($token);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);
        $resp->setData(["token" => $user->getConfirmationToken()]);
        $this->sendMailReset($email, $user->getConfirmationToken());
        return new JsonResponse($resp);
    }

    /**
     * Reset password
     *
     * @Route(
     *     methods={"POST"},
     *     path="/profile/api-password-reset/{token}",
     *     name="modify_password"
     * )
     */
    public function resetAction(Request $request, $token)
    {
        if($request->get('password')) {
            $password = $request->get('password');
        }else {
            $password = $request->query->get('password');
        }
        if (!$password) {
            return new JsonResponse(['error' => Response::HTTP_CONTINUE,'message' => 'Password not valid.']);
        }
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);
        if (null === $user) {
            return new JsonResponse(
                [
                    'error' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'The user with confirmation token does not exist for value =' . $token
                ]
            );
        }
        if ($request->getMethod() == "POST") {
            $user->setPlainPassword($password);
            $user->setConfirmationToken(null);
            $userManager->updateUser($user, true);
            return new JsonResponse(
                [
                    "code" => Response::HTTP_OK,
                    "message" => "Password resetting"
                ]
            );
        }
    }


    /**
     * @param $email
     * @param $token
     */
    public function sendMailReset($email, $token)
    {
        $url = $this->container->getParameter("host_front")."/#/fr/reinitialiser/".$token;
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::RESET_PASSWORD, 'deletedAt' => null],
            ['id' => 'DESC'], 1
        );
        $dataFrom['send_by'] = $modelEMail[0]->getEmitter();
        $template = $modelEMail[0]->getTemplate();
        $modele = ["__url__"];
        $real = [$url];
        $template = str_replace($modele, $real, $template);
        $this->container->get('app.mailer')->sendMailGrid("Resset password", $email, $template, $dataFrom);
    }
}
