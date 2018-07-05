<?php
namespace App\Controller\Admin;

use App\Entity\PartnerPageDetails;
use App\Form\Handler\PageDetailsHandler;
use App\Form\Handler\PartnerHandler;
use App\Form\Type\PageDetailsType;
use App\Form\Type\PartnerType;
use App\Manager\PageDetailsManager;
use App\Manager\PartnerManager;
use App\Services\PasswordEncoder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PartnerPageDetailsController
 * @package App\Controller\Admin
 * @Route("/admin/", name="admin_")
 */
class PartnerPageDetailsController extends BaseController
{

    /**
     * Partner list
     *
     * @Route("pages/details", defaults={"_format"="html"}, methods={"GET"}, name="notificationdetails")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $partner = $this->get(PartnerManager::SERVICE_NAME)->getPartnerParent();
        return $this->render('admin/pages/list.html.twig', ['list' => $partner]);
    }

    /**
     * add / edit partner
     * @Route("pages/details/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="notificationdetails_edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $partner = $this->get(PartnerManager::SERVICE_NAME)->find($request->get('id'));
        if($partner->getActivePageDetails()) {
            $form = $this->createForm(PageDetailsType::class, $partner->getActivePageDetails(), []);
        } else {
            $pageDetails = new PartnerPageDetails();
            $pageDetails->setPartner($partner);
            $pageDetails->setLogo(null);
            $this->get(PageDetailsManager::SERVICE_NAME)->saveAndFlush($pageDetails);
            $form = $this->createForm(PageDetailsType::class, $pageDetails, []);
        }

        $formHandler = new PageDetailsHandler(
            $form,
            $request,
            $this->get('app.page_details_manager'),
            $this->get('event_dispatcher'),
            $this->getParameter('upload_directory')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_notificationdetails_edit', ['id' => $request->get('id')]);
        }
        return $this->render(
            'admin/pages/edit.html.twig',
            ['form' => $form->createView(), 'partner' => $partner, ]
        );
    }


}