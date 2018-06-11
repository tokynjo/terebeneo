<?php
namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class NotificationHandler
{
    protected $request;
    protected $form;
    protected $notificationManager;

    /**
     * NotificationHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param null $notificationManager
     */
    public function __construct(Form $form, Request $request, $notificationManager = null){
        $this->form = $form;
        $this->request = $request;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->form->getData()->setStatus(Constant::STATUS_DISABLED);
            return $this->notificationManager->saveAndFlush($this->form->getData());
        }

        return false;
    }
}
