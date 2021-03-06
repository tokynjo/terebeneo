<?php

namespace App\Manager;

use App\Entity\Constants\Constant;
use App\Event\PartnerEvent;
use App\Services\NeobeApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PartnerManager extends BaseManager
{
    const SERVICE_NAME = 'app.partner_manager';

    private $validationLogManager;
    private $dispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        ValidationLogManager $validationLogManager,
        $dispatcher = null
    ){
        parent::__construct($entityManager, $class);
        $this->validationLogManager = $validationLogManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * get only partner parent
     * @return array
     */
    public function getPartnerParent() {
        $partners = $this->findAll();
        foreach ($partners as $key => $partner) {
            if (!is_null($partner->getParent())) {
                unset($partners[$key]);
            }

        }

        return $partners;
    }

    /**
     * get partner details by token
     *
     * @param null $token
     * @return object
     */
    public function etape1($token = null){
        $partener = $this->findOneBy(["hash" => $token]);
        return $partener;
    }

    /**
     * @param Request $request
     * @param null $token
     * @return object
     */
    public function etape2(Request $request, $token = null){
        $partner = $this->findOneBy(["hash"=>($token ? $token : "")]);
        if($partner) {
            $this->entityManager->beginTransaction();
            try{
                $existLog = $this->validationLogManager->findBy(
                    [
                        "etape" => Constant::STEP_TWO,
                        "partner" => $partner
                    ]
                );
                if (!$existLog) {
                    $validation = $this->validationLogManager->createNew();
                    $validation->setPartner($partner);
                    $validation->setEtape(Constant::STEP_TWO);
                    $this->saveAndFlush($validation);
                }
                if ($request->getMethod() == "POST") {
                    $existLog = $this->validationLogManager->findBy(
                        [
                            "etape" => Constant::STEP_ONE,
                            "partner" => $partner
                        ]
                    );
                    if (!$existLog) {
                        //creation du compte noebe du partenaire
                        $partnerEvent = new PartnerEvent($partner);
                        $nbLicence = 0;
                        $volumeParLicence = 0;
                        if($request->get('create-account')) {
                            $nbLicence = $request->get('nb_licences_a_creer');
                            $volumeParLicence = $request->get('volume_par_licence_Go');
                        }
                        $partnerEvent->setNbLicencesToCreate($nbLicence)
                            ->setVolumeParLicenceGo($volumeParLicence);
                        $this->dispatcher->dispatch(PartnerEvent::PARTNER_CLIENT_ON_VALIDATE_ACCOUNT, $partnerEvent);

                        $validation = $this->validationLogManager->createNew();
                        $validation->setPartner($partner);
                        $validation->setEtape(Constant::STEP_ONE);
                        $this->saveAndFlush($validation);
                    } else {
                        dump("log exist");
                    }
                }
                $this->entityManager->commit();
                return $partner;
            } catch (\Exception $e) {
                $this->entityManager->getConnection()->rollback();
                $this->entityManager->close();
                return false;
            }
        }
        return $partner;
    }

    /**
     * @param bool $deleted
     * @return array
     */
    public function getClientSimulation($deleted = false) {
        $simulation = array();
        if ($deleted) {
            $partners = $this->findAll();
        } else {
            $partners = $this->findBy(['deleted' => Constant::NO] );
        }
        if (sizeof($partners) > 0) {
            foreach ($partners as $key => $p) {
                if ( $p->getSimulation() == Constant::YES) {
                    array_push($simulation, $p);
                }
            }
        }

        return $simulation;
    }

    /**
     * soft delete a partner or client
     * if a partner then set api user to deleted
     * @param $id
     * @return object
     */
    public function softDeleted ($id)
    {
        $entity = $this->find($id);
        if (!is_null($entity)) {
            $entity->setDeleted(Constant::DELETED);
            $mail = $entity->getMail().'_del_'.date('Ymd.His');
            $entity->setMail($mail);
            $this->saveAndFlush($entity);
            if(!is_null($entity->getUser()[0])) {
                $user = $entity->getUser();
                $user->setDeleted(Constant::DELETED)
                    ->setUsername($mail)
                    ->setUsernameCanonical($mail)
                    ->setEmail($mail)
                    ->setEmailCanonical($mail);
                $this->saveAndFlush($user);
            }
        }

        return $entity;
    }
    
    /**
     * @param $partener
     */
    public function sendPassword($partener){
        $partnerEvent = new PartnerEvent($partener);
        $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_SEND_PASSWORD, $partnerEvent);
    }
}
