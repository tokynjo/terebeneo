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

class ClientModificationHandler
{
    protected $request;
    protected $form;
    protected $manager;

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
        $resp = false;
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->manager->saveAndFlush($this->form->getData());
            $resp = true;
        }
        return $resp;
    }
}
