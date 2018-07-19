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
        echo(" service");
        $partner = $this->findOneBy(["hash"=>($token ? $token : "")]);
        if($partner) {
            dump("partner :". $partner->getId());
            $this->entityManager->beginTransaction();
            try{
                dump('try');
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
                    }
                }
                $this->entityManager->commit();
                dump('ok');
                die('');
                return $partner;
            } catch (\Exception $e) {
                dump($e->getMessage());
                $this->entityManager->getConnection()->rollback();
                $this->entityManager->close();
                die('');
                return false;
            }
        }
        die('');
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
}
