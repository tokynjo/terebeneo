<?php
namespace App\Controller\Admin;

use App\Entity\HeaderFooter;
use App\Entity\Notification;
use App\Entity\NotificationContent;
use App\Entity\User;
use App\Form\Handler\HeaderFooterHandler;
use App\Form\Handler\NotificationContentHandler;
use App\Form\Handler\NotificationHandler;
use App\Form\Type\HeaderFooterType;
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
     * @Route("/notification/header", defaults={"_format"="html"}, methods={"GET"}, name="notificationheader_index")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $headers = $this->get(HeaderFooterManager::SERVICE_NAME)->getHeaderFooterActive();
        return $this->render('admin/header/list.html.twig', ['list' => $headers]);
    }

    /**
     * create or edit header
     *
     * @Route("/notification/header/create", defaults={"_format"="html"}, methods={"GET","POST"}, name="notificationheader_create")
     * @Route("/notification/header/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="notificationheader_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $header = $this->get(HeaderFooterManager::SERVICE_NAME)->find($request->get('id'));
        $form = $this->createForm(HeaderFooterType::class, $header);
        $formHandler = new HeaderFooterHandler($form, $request, $this->get('app.header_footer_manager'), $this->get('event_dispatcher'));
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_notificationheader_index');
        }

        return $this->render('admin/header/edit.html.twig', ['form' => $form->createView(), 'header' => $header]);
    }

    /**
     * delete header
     *
     * @Route("/notification/header/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="notificationheader_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $header = $this->get(HeaderFooterManager::SERVICE_NAME)->find($request->get('id'));
        if ($header) {
            $this->get(HeaderFooterManager::SERVICE_NAME)->delete($header);
            return $this->redirectToRoute('admin_notificationheader_index');
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
