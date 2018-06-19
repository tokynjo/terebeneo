<?php
namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\PartnerEvent;
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
                if(!$data->getId()){
                    //hash
                    $data->setHash(sha1(date('Ymdhis')));
                    $this->partnerManager->saveAndFlush($data);
                    //creating user api
                    $partnerEvent = new PartnerEvent($data);
                    $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_CREATE, $partnerEvent);
                }
                $em->commit();
                return true;
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                return false;
            }
        }
        return false;
    }
}
