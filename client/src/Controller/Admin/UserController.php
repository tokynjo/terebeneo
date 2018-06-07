<?php

namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Form\Type\UserType;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller\Admin
 * @Route("/admin", name="admin_")
 */
class UserController extends BaseController
{

    /**
     * First step
     *
     * @Route("/user", defaults={"_format"="html"}, methods={"GET"}, name="user_index")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $users = $this->get(UserManager::SERVICE_NAME)->findBy(['enabled' => Constant::YES]);
        return $this->render('admin/user/list.html.twig', ['users' => $users, 'roles'=>Constant::$roles]);
    }

    /**
     * create user
     *
     * @Route("/user/create", defaults={"_format"="html"}, methods={"GET","POST"}, name="user_create")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $fosUserManager = $this->get('fos_user.user_manager');
        $formHandler = new UserHandler(
            $form,
            $request,
            $this->getDoctrine()->getManager(),
            $fosUserManager,
            $this->get('event_dispatcher')
        );
        if($formHandler->process()) {
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="user_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->get(UserManager::SERVICE_NAME)->find($request->get('id'));
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'action' => $this->generateUrl('admin_user_edit', ['id'=>$request->get('id')])
            ]
        );
        $formHandler = new UserHandler(
            $form,
            $request,
            $this->getDoctrine()->getManager(),
            $this->get('fos_user.user_manager'),
            $this->get('event_dispatcher')
        );
        if($formHandler->process()) {
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * delete user
     *
     * @Route("/user/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="user_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        if ($user = $entityManager->getRepository("App:User")->find($id)) {
            //do not delete user physically. Just set it not enabled
            $user->setEnabled(false);
            $user->setDeleted(true);
            $user->setUsernameCanonical($user->getUsernameCanonical().Constant::DELETED_SALT.date('YmdHis'));
            $user->setEmailCanonical($user->getEmailCanonical().Constant::DELETED_SALT.date('YmdHis'));
            $this->get('fos_user.user_manager')->updateUser($user);
            return $this->redirectToRoute('admin_user_index');
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('users.user_not_found', ['%id%'=>$id], 'label', 'fr')
            );
        }
    }
}
