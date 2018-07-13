<?php
namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Models\Api\ApiResponse;
use App\Services\NeobeTerApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientHandler
{
    protected $request;
    protected $form;
    protected $manager;
    protected $apiNeobeTer;

    /**
     * @param Form $form
     * @param Request $request
     * @param null $manager
     * @param NeobeTerApiService $apiNeobeTer
     */
    public function __construct(Form $form, Request $request, $manager = null, NeobeTerApiService $apiNeobeTer = null){
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
        $this->apiNeobeTer = $apiNeobeTer;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $resp = true;
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            try {
                $data = $this->apiNeobeTer->createClient($this->form->getData(), true);
                if($data->getCode() != Response::HTTP_OK) {
                    $this->request->getSession()->getFlashBag()->add('error', $data->getMessage());
                    $resp = false;
                } else {
                    $this->request->getSession()->getFlashBag()->add('success', 'success');
                    $resp = true;
                }
            } catch (\Exception $e) {
                $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
                $resp = false;
            }
        } else {
            $resp = false;
        }

        return $resp;
    }
}
