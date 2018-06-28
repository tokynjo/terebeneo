<?php

namespace App\Manager;

use App\Entity\Constants\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PartnerManager extends BaseManager
{
    const SERVICE_NAME = 'app.partner_manager';
    private $validationLogManager;
    public function __construct(EntityManagerInterface $entityManager, $class, ValidationLogManager $validationLogManager)
    {
        parent::__construct($entityManager, $class);
        $this->validationLogManager = $validationLogManager;
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
        $partener = $this->findOneBy(["hash"=>($token ? $token : "")]);
        if($partener) {
            $existLog = $this->validationLogManager->findBy(
                [
                    "etape" => Constant::STEP_TWO,
                    "partner" => $partener
                ]
            );
            if (!$existLog) {
                $validation = $this->validationLogManager->createNew();
                $validation->setPartner($partener);
                $validation->setEtape(Constant::STEP_TWO);
                $this->saveAndFlush($validation);
            }
            if ($request->getMethod() == "POST") {
                $existLog = $this->validationLogManager->findBy(
                    [
                        "etape" => Constant::STEP_ONE,
                        "partner" => $partener
                    ]
                );
                if (!$existLog) {
                    $validation = $this->validationLogManager->createNew();
                    $validation->setPartner($partener);
                    $validation->setEtape(Constant::STEP_ONE);
                    $this->saveAndFlush($validation);

                }


            }
        }
        return $partener;
    }
}
