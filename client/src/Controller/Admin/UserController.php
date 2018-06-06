<?php

namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Form\Type\UserType;
use App\Manager\UserManager;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
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
        //var_dump($users[0]); die;
        return $this->render('admin/user/list.html.twig', ['users' => $users]);
    }

    /**
     * First step
     *
     * @Route("/user/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="user_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository("App:User")->find($request->get('id'));
        if (!$user) {
            $this->get('session')->getFlashBag()->add('error', 'Utilisateur n\'existe pas.');
            return $this->redirectToRoute('admin_user_index');
        }
        if ($request->get('id') != $this->getUser()->getId()) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')
                && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            ) {
                throw $this->createAccessDeniedException();
            }
        }

        $dispatcher = $this->get('event_dispatcher');
        $userManager = $this->get('fos_user.user_manager');
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'action' => $this->generateUrl('admin_user_edit', ['id' => $request->get('id')])
            ]
        );
        /*$formHandler = new RegistrationHandler($form, $request, $userManager);

        $form = $formHandler->getForm();
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_user_index');
        }*/

       return $this->render('admin/user/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * First step
     *
     * @Route("/user/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="admin_user_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $users = $this->get(UserManager::SERVICE_NAME)->findBy(['enabled' => Constant::YES]);
        return $this->render('admin/user/list.html.twig', ['users' => $users]);
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
