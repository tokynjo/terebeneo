<?php

namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordHandler
{
    protected $request;
    protected $form;
    protected $entityManager;
    protected $fosUserManager;
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
        EntityManagerInterface $entityManager,
        $fosUserManager = null,
        EventDispatcherInterface $eventDispatcher
    ){
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->fosUserManager = $fosUserManager;
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
            $data->setPlainPassword($this->request->get('user_password')['plainPassword']['first']);
            $this->fosUserManager->updateUser($data);
            $this->entityManager->flush();

            return true;
        }
        return false;
    }
}
