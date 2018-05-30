<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller\Admin
 * @Route("/admin", name="admin_controller")
 */
class UserController extends BaseController
{

    /**
     * First step
     *
     * @Route("/user", defaults={"_format"="html"}, methods={"GET"}, name="admin_user_index")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('admin/user/index.html.twig', []);
    }

    /**
     * First step
     *
     * @Route("/login", defaults={"_format"="html"}, methods={"GET"}, name="admin_user_login")
     * @param Request $request
     * @return Response
     */

    public function loginAction(Request $request)
    {
        return $this->render('admin/user/login.html.twig', []);
    }

    /**
     * First step
     *
     * @Route("/reset-password", defaults={"_format"="html"}, methods={"GET"}, name="admin_user_reset_password")
     * @param Request $request
     * @return Response
     */

    public function resetPasswordAction(Request $request)
    {
        return $this->render('admin/user/reset-password.html.twig', []);
    }
}
