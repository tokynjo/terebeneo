<?php
namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Form\Handler\PartnerHandler;
use App\Form\Type\PartnerType;
use App\Manager\PartnerManager;
use App\Services\PasswordEncoder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PartnerController
 * @package App\Controller\Admin
 * @Route("/admin", name="admin_")
 */
class PartnerController extends BaseController
{

    /**
     * Partner list
     *
     * @Route("/partner", defaults={"_format"="html"}, methods={"GET"}, name="partner_index")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $headers = $this->get(PartnerManager::SERVICE_NAME)->findBy(
            ['simulation' => Constant::NO, 'deleted' => Constant::NOT_DELETED ]
        );
        return $this->render('admin/partner/list.html.twig', ['list' => $headers]);
    }

    /**
     * add / edit partner
     * @Route("/partner/create", defaults={"_format"="html"}, methods={"GET","POST"}, name="partner_create")
     * @Route("/partner/edit/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="partner_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $partner = $this->get(PartnerManager::SERVICE_NAME)->find($request->get('id'));
        //var_dump($partner->getUser()[1]->getId()); die;
        $form = $this->createForm(PartnerType::class, $partner, []);
        $formHandler = new PartnerHandler($form, $request, $this->get('app.partner_manager'), $this->get('event_dispatcher'));
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_partner_index');
        }
        return $this->render(
            'admin/partner/edit.html.twig',
            [
                'form' => $form->createView(),
                'partner' => $partner
            ]
        );
    }

    /**
     * delete notification
     *
     * @Route("/partner/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="partner_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        if ($notification = $this->get(PartnerManager::SERVICE_NAME)->find($request->get('id'))) {
            $this->get(PartnerManager::SERVICE_NAME)->softDeleted($notification);
            return $this->redirect($request->headers->get('referer'));
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('page.content_not_found', ['%id%' => $request->get('id')], 'label', 'fr')
            );
        }
    }

    /**
     * Send password
     *
     * @Route("/partner/sendPassword/{id}", defaults={"_format"="html"}, methods={"GET"}, name="partner_send_password")
     * @param Request $request
     * @return Response
     */
    public function sendPassword(Request $request, $id)
    {
        if ($notification = $this->get(PartnerManager::SERVICE_NAME)->find($id)) {
            $this->get(PartnerManager::SERVICE_NAME)->sendPassword($notification);
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('page.content_not_found', ['%id%' => $request->get('id')], 'label', 'fr')
            );
        }
        return $this->redirectToRoute('admin_partner_edit',["id" => $id]);
    }
}