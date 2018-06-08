<?php

namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Form\Type\UserType;
use App\Manager\UserManager;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * User list
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
     * edit user
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

    /**
     * Resetting user password: submit form and send email.
     *
     * @Route("/user/resetting/send-email", methods={"POST"}, name="user_resetting_send_mail")
     * @param Request $request
     * @return Response
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /* Dispatch init event */
        $event = new GetResponseNullableUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $ttl = $this->container->getParameter('fos_user.resetting.retry_ttl');

        if (null !== $user && !$user->isPasswordRequestNonExpired($ttl)) {
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            if (null === $user->getConfirmationToken()) {
                /** @var $tokenGenerator TokenGeneratorInterface */
                $tokenGenerator = $this->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            /* Dispatch confirm event */
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            //generate mail content
            $url = $this->generateUrl(
                'fos_user_resetting_reset',
                ['token' => $user->getConfirmationToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $rendered = $this->renderView(
                '@FOSUser/Resetting/email.txt.twig',['user' => $user,'confirmationUrl' => $url]
            );
            //sendgrid send mail
            //$this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], (string) $user->getEmail());
            $data = array();
            $sender = new User();
            $sender
                ->setEmail($this->getParameter('no_reply_address'))
                ->setNom($this->getParameter('no_reply_address'));
            $data['send_by'] = $sender;
            $sendTo = [$user->getEmail()];
            $this->get('app.mailer')->sendMailGrid(
                "Récupération de mot de passe",
                $sendTo,
                $rendered,
                $data
            );
            $user->setPasswordRequestedAt(new \DateTime());
            $this->get('fos_user.user_manager')->updateUser($user);

            /* Dispatch completed event */
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }
        }

        /*return $this->redirectToRoute(
            $this->generateUrl('admin_user_resetting_success', ['username' => $username])
        );*/
        return new RedirectResponse($this->generateUrl('admin_user_resetting_success', []));
    }

    /**
     * Resetting user password: submit form and send email.
     *
     * @Route("/user/resetting/success", methods={"GET"}, name="user_resetting_success")
     * @param Request $request
     * @return Response
     */
    public function sendEmailSuccessAction(Request $request)
    {
        return $this->render('@FOSUser/Resetting/check_email.html.twig', ['username' => '$username']);
    }
}
