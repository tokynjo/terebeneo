<?php
namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Entity\Partner;
use App\Form\Handler\ClientHandler;
use App\Form\Handler\PartnerHandler;
use App\Form\Type\ClientType;
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
 * @Route("/admin/simulation", name="admin_simulation_")
 */
class SimulationController extends BaseController
{

    /**
     * Partner similation list
     *
     * @Route("/clients", defaults={"_format"="html"}, methods={"GET"}, name="index")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $partner = $this->get(PartnerManager::SERVICE_NAME)->getClientSimulation();
        return $this->render('admin/simulation/list.html.twig', ['list' => $partner]);
    }
    /**
     * add client
     *
     * @Route("/add-client", defaults={"_format"="html"}, methods={"GET","POST"}, name="client_add")
     * @param Request $request
     * @return Response
     */

    public function addAction(Request $request)
    {
        $client = new Partner();
        $form = $this->createForm(ClientType::class, $client, []);
        $formHandler = new ClientHandler(
            $form,
            $request,
            $this->get('app.partner_manager'),
            $this->get('api.neobe_ter.create_account')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_simulation_index');
        }
        return $this->render(
            'admin/simulation/add_client.html.twig',
            [
                'form' => $form->createView(),
                'partner' => $client
            ]
        );
    }

    /**
     * delete notification
     *
     * @Route("/client/delete/{id}", defaults={"_format"="html"}, methods={"GET"}, name="client_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $client = $this->get(PartnerManager::SERVICE_NAME)->find($request->get('id'));
        if ($client) {
            $this->get(PartnerManager::SERVICE_NAME)->delete($client);
            return $this->redirectToRoute('admin_simulation_index');
        } else {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('page.content_not_found', ['%id%' => $request->get('id')], 'label', 'fr')
            );
        }
    }


    /**
     * add client
     *
     * @Route("/client-details/{id}", defaults={"_format"="html"}, methods={"GET","POST"}, name="client_details")
     * @param Request $request
     * @return Response
     */

    public function detailsAction(Request $request)
    {
        $client = $this->get(PartnerManager::SERVICE_NAME)->find($request->get('id'));
        $form = $this->createForm(ClientType::class, $client, []);
        $formHandler = new ClientHandler(
            $form,
            $request,
            $this->get('app.partner_manager'),
            $this->get('api.neobe_ter.create_account')
        );
        if ($formHandler->process()) {
            return $this->redirectToRoute('admin_simulation_index');
        }
        return $this->render(
            'admin/simulation/details_client.html.twig',
            [
                'form' => $form->createView(),
                'partner' => $client
            ]
        );
    }
}