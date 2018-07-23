<?php
namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\PartnerEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PartnerHandler
{
    protected $request;
    protected $form;
    protected $partnerManager;
    protected $dispatcher;

    /**
     * @param Form $form
     * @param Request $request
     * @param $partnerManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Form $form,
        Request $request,
        $partnerManager,
        EventDispatcherInterface $eventDispatcher = null
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
        $em = $this->partnerManager->getEntityManager();
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $em->getConnection()->beginTransaction();
            try {
                $data = $this->form->getData();
                $data->setDeleted(Constant::NO);
                if(!$data->getId()){ //creation
                    //hash
                    $data->setHash(sha1(date('Ymdhis')));
                    $data->setMobile($data->getPhone());
                    $this->partnerManager->saveAndFlush($data);
                    //creating user api
                    $partnerEvent = new PartnerEvent($data);
                    $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_CREATE, $partnerEvent);
                } else { // edition
                    $this->partnerManager->saveAndFlush($data);
                    $partnerEvent = new PartnerEvent($data);
                    $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_EDIT, $partnerEvent);

                }
                $em->commit();
                return true;
            } catch (\Exception $e) {
                $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
                $em->getConnection()->rollback();
                $em->close();
                return false;
            }
        }
        return false;
    }
}
