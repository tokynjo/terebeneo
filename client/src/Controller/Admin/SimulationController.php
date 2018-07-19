<?php
namespace App\Controller\Admin;

use App\Entity\Constants\Constant;
use App\Entity\Partner;
use App\Form\Handler\ClientHandler;
use App\Form\Handler\ClientModificationHandler;
use App\Form\Handler\PartnerHandler;
use App\Form\Type\ClientCreationDateType;
use App\Form\Type\ClientModificationType;
use App\Form\Type\ClientType;
use App\Form\Type\PartnerType;
use App\Manager\PartnerManager;
use App\Services\PasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
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
        $form = $this->createForm(ClientModificationType::class, $client, []);
        $formHandler = new ClientModificationHandler(
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

    /**
     * confirm account creation
     * @Route("/simulation/create-account/{token}/{id}", defaults={"page": "1", "_format"="html"}, methods={"GET","POST"}, name="create_account")
     * @param Request $request
     * @param null $token
     * @return mixed
     */
    public function createAccountAction(Request $request, $token = null)
    {
        $this->get(PartnerManager::SERVICE_NAME)->etape2($request, $token);
        return $this->redirectToRoute('admin_simulation_client_details', ["token" => $token, 'id' =>$request->get('id')]);

    }

    /**
     * send notification to client created via simulation interface
     *
     * @Route("/send", defaults={"_format"="json"}, methods={"GET","POST"}, name="send_notifications")
     * @param Request $request
     * @return Response
     */
    public function sendAction(Request $request)
    {
        try {
            $application = new Application($this->get('kernel'));
            $application->setAutoExit(false);
            $input = new ArrayInput(array(
                'command' => 'neobe_ter:activation',
                // (optional) pass options to the command
                '--simulation' => NULL,
            ));
            // You can use NullOutput() if you don't need the output
            $output = new NullOutput();
            $application->run($input, $output);
            // return the output, don't use if you used NullOutput()
            //$content = $output->fetch();
            // return new Response(""), if you used NullOutput()
            $response =  new Response('Success', Response::HTTP_OK);
        } catch (Exception $e) {
            $response = new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}