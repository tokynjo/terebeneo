<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller\Admin
 * @Route("/admin", name="admin_")
 */
class IndexController extends BaseController
{

    /**
     * First step
     *
     * @Route("/", defaults={"_format"="html"}, methods={"GET"}, name="index")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('steps_one', array('token' => 0));
        //return $this->render('admin/index.html.twig', []);
    }
}
