<?php

namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use App\Services\PasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
class UserHandler
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
            $data->setUsername($data->getEmail());
            if ($this->request->get('id')) {
                if (in_array('ROLE_USER',$data->getRoles())) {
                    //if user api
                    $user = $this->entityManager->getRepository('App:User')->find($data->getId());
                    $partner = $user->getPartner();
                    $partner->setMail($data->getEmail());
                    $this->entityManager->persist($partner);
                }
            } else {
                $pwdEncoder = new PasswordEncoder();
                $data->setPlainPassword($pwdEncoder->random_str('alphanum', Constant::PASSWORD_LENGTH));
            }
            $data->setEnabled(Constant::ENABLED);
            $data->setDeleted(Constant::NO);
            $this->fosUserManager->updateUser($data);
            $this->entityManager->flush();

            /**
             * sending email notification username/mdp
             */
            //$userEvent = new UserEvent($data);
            //$this->dispatcher->dispatch($userEvent::USER_ON_CREATE, $userEvent);
            return true;
        }
        return false;
    }
}
