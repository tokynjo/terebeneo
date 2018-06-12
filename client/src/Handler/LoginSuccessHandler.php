<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 08/06/2018
 * Time: 09:45
 */
namespace App\Handler;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $authorizationChecker;
    protected $request;
    protected $tokenStorage = null;

    public function __construct(
        Router $router,
        AuthorizationChecker $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
        $this->request = $requestStack->getCurrentRequest();
        $this->tokenStorage = $tokenStorage;

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            //authorise ROLE_ADMIN admin only
            $response = new RedirectResponse($this->router->generate('admin_index'));
        } else {
            //if NOT ROLE_ADMIN
            try {
                $this->request->getSession()->invalidate();
                $this->tokenStorage->setToken(null);
                $this->request->getSession()
                    ->getFlashBag()
                    ->add(Security::ACCESS_DENIED_ERROR, 'login.403_error')
                ;
                $response = new RedirectResponse($this->router->generate('fos_user_security_login'));
            } catch (\Exception $e) {
                return false;
            }
        }
        return $response;
    }

}