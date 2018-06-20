<?php
namespace App\Manager;

use App\Entity\ApiResponse;
use App\Entity\Constants\Constant;
use App\Entity\Partner;
use App\Event\PartnerEvent;
use App\Services\ApiRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartnerManager
 *
 * @package App\Manager
 */
class PartnerManager extends BaseManager
{
    const SERVICE_NAME = 'app.partner_manager';
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     *
     * @var type
     */
    protected $class;

    protected $dispatcher = null;

    /**
     *
     * @param EntityManagerInterface $entityManager
     * @param type                   $class
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        $eventDispatcher = null
    ){
        $this->entityManager = $entityManager;
        $this->class = $class;
        $this->repository = $this->entityManager->getRepository($this->class);
        $this->dispatcher = $eventDispatcher;
    }


    /**
     * create partner in neobe-ter
     * then send validation link in email with hash
     * @param Request $request
     * @return ApiResponse
     */
    public function createPartner(Request $request)
    {
        $apiRequest = new ApiRequest();
        $resp = new ApiResponse();
        $is_valid = true;
        $partner = new Partner();
        //$partnerMgr = $this->container->get(PartnerManager::SERVICE_NAME);
        //test society name unicity
        if ($apiRequest->getBodyRawParam('company_name')!= null) {
            if (!is_null($this->findOneBy(['name' => $apiRequest->getBodyRawParam('company_name')] ))) {
                $resp->setCode(400)
                    ->setMessage('Parameter company_name already exists');
                return $resp;
            }
        } else {
            $resp->setCode(400)
                ->setMessage('Parameter company_name is mandatory');
            return $resp;
        }
        $partner->setName($apiRequest->getBodyRawParam('company_name'));

        //test society contact mail unicity
        $email = $apiRequest->getBodyRawParam('email');
        if ($email != null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!is_null($this->findOneBy(['mail' => $apiRequest->getBodyRawParam('email')] ))) {
                $resp->setCode(400)
                    ->setMessage('Parameter email already exists');
                return $resp;
            }
        } else {
            $resp->setCode(400)
                ->setMessage('Parameter email is mandatory. Verify the email format');
            return $resp;
        }
        $partner->setMail($email);

        $civility = $this->entityManager->getRepository('App:Civility')->find(
            $apiRequest->getBodyRawParam('civility', Constant::DEFAULT_CIVILITY)
        );
        $partner->setCivility($civility);

        //test nb licence
        $nbLicence = $apiRequest->getBodyRawParam('nb_licence');
        if (is_null($nbLicence) || !in_array($nbLicence, Constant::$neobeNbLicense)) {
            $resp->setCode(400)
                ->setMessage('Invalid parameter : nb_licence');
            return $resp;
        }
        $partner->setNbLicense($nbLicence);
        //test volume size
        $volumeSize = $apiRequest->getBodyRawParam('volume_size');
        if (is_null($volumeSize) || !in_array($volumeSize, Constant::$neobeVolumeSize)) {
            $resp->setCode(400)
                ->setMessage('Invalid parameter : volume_size');
            return $resp;
        }
        $partner->setVolumeSize($volumeSize);

        $partner->setParent($apiRequest->getBodyRawParam(1))
            ->setAddress1($apiRequest->getBodyRawParam('address1'))
            ->setAddress2($apiRequest->getBodyRawParam('address2'))
            ->setZipCode($apiRequest->getBodyRawParam('zip_code'))
            ->setCountry($apiRequest->getBodyRawParam('country'))
            ->setCity($apiRequest->getBodyRawParam('city'))
            ->setFirstname($apiRequest->getBodyRawParam('firstname'))
            ->setLastname($apiRequest->getBodyRawParam('lastname'))
            ->setPhone($apiRequest->getBodyRawParam('phone'))
            ->setDeleted(Constant::NOT_DELETED);
        $partner->setHash(sha1(uniqid().date('YmdHis')));

        $this->entityManager->persist($partner);
        $this->entityManager->flush();

        //creating user api
        $partnerEvent = new PartnerEvent($partner);
        $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_CREATE, $partnerEvent);
        return $resp;
    }
}
