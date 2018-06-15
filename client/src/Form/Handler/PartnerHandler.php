<?php

namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class PartnerHandler
{
    protected $request;
    protected $form;
    protected $partnerManager;
    protected $dispatcher;

    /**
     * UserHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param null $fosUserManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Form $form,
        Request $request,
        $partnerManager,
        EventDispatcherInterface $eventDispatcher
    ){
        $this->form = $form;
        $this->request = $request;
        $this->partnerManager = $partnerManager;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $data = $this->form->getData();
            $data->setDeleted(Constant::NO);
            $this->partnerManager->saveAndFlush($data);

            return true;
        }
        return false;
    }
}
