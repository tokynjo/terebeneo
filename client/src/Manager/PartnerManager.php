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
                    $partnerEvent->setNbLicencesToCreate($request->get('nb_licences_a_creer'))
                        ->setVolumeParLicenceGo($request->get('volume_par_licence_Go'));
                    $this->dispatcher->dispatch(PartnerEvent::PARTNER_CLIENT_ON_VALIDATE_ACCOUNT, $partnerEvent);

                    $validation = $this->validationLogManager->createNew();
                    $validation->setPartner($partner);
                    $validation->setEtape(Constant::STEP_ONE);
                    $this->saveAndFlush($validation);

                }


            }
        }
        return $partner;
    }
}
