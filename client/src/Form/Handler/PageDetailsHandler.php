<?php

namespace App\Form\Handler;

use App\Entity\Constants\Constant;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class PageDetailsHandler
{
    protected $request;
    protected $form;
    protected $headerManager;
    protected $dispatcher;
    protected $uploadDir;

    /**
     * @param Form $form
     * @param Request $request
     * @param $headerManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $uploadDir
     */
    public function __construct(
        Form $form,
        Request $request,
        $headerManager,
        EventDispatcherInterface $eventDispatcher,
        $uploadDir = ''
    ){
        $this->form = $form;
        $this->request = $request;
        $this->headerManager = $headerManager;
        $this->dispatcher = $eventDispatcher;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return bool
     */
    public function process()
    {

        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $data = $this->form->getData();
            //logo
            $fileLogo = $this->form['logoUpload']->getData();
            if ($fileLogo) {
                $logoName = 'logo.'.uniqid().'.'. $fileLogo->guessExtension();
                $fileLogo->move($this->uploadDir.DIRECTORY_SEPARATOR.$data->getPartner()->getId(), $logoName);
                $data->setLogo($logoName);
            }
            $imageLeft = $this->form['imageLeftUpload']->getData();
            if ($imageLeft) {
                $name = 'network.'.uniqid().'.'. $imageLeft->guessExtension();
                $imageLeft->move($this->uploadDir.DIRECTORY_SEPARATOR.$data->getPartner()->getId(), $name);
                $data->setImageLeft($name);
            }
            $data->setDeleted(Constant::NO);
            $this->headerManager->saveAndFlush($data);
            return true;
        }
        return false;
    }
}
