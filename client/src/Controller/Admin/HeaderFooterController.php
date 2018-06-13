<?php
namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\NotificationContent;
use App\Entity\User;
use App\Form\Handler\NotificationContentHandler;
use App\Form\Handler\NotificationHandler;
use App\Form\Type\NotificationContentType;
use App\Form\Type\NotificationType;
use App\Manager\HeaderFooterManager;
use App\Manager\NotificationContentManager;
use App\Manager\NotificationManager;
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
class HeaderFooterController extends BaseController
{

    /**
     * User list
     *
     * @Route("/notification/header", defaults={"_format"="html"}, methods={"GET"}, name="notification_header_index")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $headers = $this->get(HeaderFooterManager::SERVICE_NAME)->findAll();
        return $this->render('admin/header/list.html.twig', ['list' => $headers]);
    }

    /**
     * create notification
     *
     * @Route("/notification/create", defaults={"_format"="html"}, methods={"GET","POST"}, name="notification_create")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $formHandler = new NotificationHandler(
            $form,
            $request,
            $this->get('app.notification_manager')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_notification_index');
        }
        return $this->render('admin/notification/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * edit notification
     * @Route("/notification/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="notification_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $notification = $this->get(NotificationManager::SERVICE_NAME)->find($request->get('id'));
        $form = $this->createForm(
            NotificationType::class,
            $notification,
            [
                'action' => $this->generateUrl('admin_notification_edit', ['id' => $request->get('id')])
            ]
        );
        $formHandler = new NotificationHandler(
            $form,
            $request,
            $this->get('app.notification_manager')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_notification_index');
        }
        return $this->render(
            'admin/notification/edit.html.twig',
            [
                'form' => $form->createView(),
                'notification' => $notification
            ]
        );
    }

    /**
     * delete notification
     *
     * @Route("/notification/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="notification_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        if ($notification = $entityManager->getRepository("App:Notification")->find($id)) {
            $this->get(NotificationManager::SERVICE_NAME)->delete($notification);
            return $this->redirectToRoute('admin_notification_index');
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('page.content_not_found', ['%id%' => $id], 'label', 'fr')
            );
        }
    }

    /**
     * edit notification message
     * @Route("/notification/{notif_id}/message/create", defaults={"_format"="html"}, methods={"GET","POST"}, name="notification_message_create")
     * @Route("/notification/{notif_id}/message/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="notification_message_edit")
     * @param Request $request
     * @return Response
     */
    public function editMessageAction(Request $request)
    {
        $notification = $this->get(NotificationManager::SERVICE_NAME)->find($request->get('notif_id'));
        if($request->get('id')) {
            $notificationContent = $this->get(NotificationContentManager::SERVICE_NAME)->find($request->get('id'));
            $url = $this->generateUrl(
                'admin_notification_message_edit',
                [
                    'id' => $request->get('id'),
                    'notif_id'=>$request->get('notif_id')
                ]
            );
        } else {
            $notificationContent = new NotificationContent();
            $url = $this->generateUrl('admin_notification_message_create', ['notif_id'=>$request->get('notif_id')]);
        }
        $form = $this->createForm(
            NotificationContentType::class,
            $notificationContent,
            [
                'action' => $url
            ]
        );
        $formHandler = new NotificationContentHandler(
            $form,
            $request,
            $this->get('app.notification_manager')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_notification_edit', ['id'=>$request->get('notif_id')]);
        }
        return $this->render(
            'admin/notification/message_edit.html.twig',
            [
                'form' => $form->createView(),
                'notificationContent' => $notificationContent,
                'notification' => $notification
            ]
        );
    }


    /**
     * delete notification message
     *
     * @Route("/notification/{notif_id}/message/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="notification_message_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteMessageAction(Request $request)
    {
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        if ($notificationContent = $entityManager->getRepository("App:NotificationContent")->find($id)) {
            $this->get(NotificationContentManager::SERVICE_NAME)->delete($notificationContent);
            return $this->redirectToRoute('admin_notification_edit', ['id'=>$request->get('notif_id')]);
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('page.content_not_found', ['%id%' => $id], 'label', 'fr')
            );
        }
    }
}
