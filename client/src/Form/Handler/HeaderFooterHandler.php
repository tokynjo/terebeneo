<?php

namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class HeaderFooterHandler
{
    protected $request;
    protected $form;
    protected $headerManager;
    protected $dispatcher;

    /**
     * @param Form $form
     * @param Request $request
     * @param $headerManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Form $form,
        Request $request,
        $headerManager,
        EventDispatcherInterface $eventDispatcher
    ){
        $this->form = $form;
        $this->request = $request;
        $this->headerManager = $headerManager;
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
            if (!$data->getId()) {
                $activeHf = $this->headerManager->getHeaderFooterActiveByPartner($data->getPartner());
                if ($activeHf) {
                    $activeHf->setDeleted(Constant::DELETED);
                    $this->headerManager->saveAndFlush($activeHf);
                }
            }
            $data->setDeleted(Constant::NO);
            $this->headerManager->saveAndFlush($data);
            return true;
        }
        return false;
    }
}
