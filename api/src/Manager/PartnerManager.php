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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
    protected $tokenStorage;

    /**
     * @param EntityManagerInterface $entityManager
     * @param type $class
     * @param null $eventDispatcher
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        $eventDispatcher = null,
        TokenStorageInterface $tokenStorage
    ){
        $this->entityManager = $entityManager;
        $this->class = $class;
        $this->repository = $this->entityManager->getRepository($this->class);
        $this->dispatcher = $eventDispatcher;
        $this->tokenStorage = $tokenStorage;
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

        //test society name unicity
        if ($apiRequest->getBodyRawParam('society')!= null) {
            if (!is_null($this->findOneBy(['name' => $apiRequest->getBodyRawParam('society')] ))) {
                $resp->setCode(400)
                    ->setMessage('Parameter society already exists');
                return $resp;
            }
        } else {
            $resp->setCode(400)
                ->setMessage('Parameter society is mandatory');
            return $resp;
        }
        $partner->setName($apiRequest->getBodyRawParam('society'));

        //test category
        $category = $apiRequest->getBodyRawParam('category');
        if (is_null($category) || !in_array($category, Constant::$partnerCategory)) {
            $resp->setCode(400)
                ->setMessage('Parameter category is not valid');
            return $resp;
        }
        $partner->setCategory($apiRequest->getBodyRawParam('category'));

        //check civility
        $civility = $apiRequest->getBodyRawParam('civility');
        if (is_null($civility) || !in_array(ucfirst($civility), Constant::$partnerCivility)) {
            $resp->setCode(400)
                ->setMessage('Parameter civility is not valid');
            return $resp;
        } else {
            $civility = $this->entityManager->getRepository('App:Civility')->findOneBy(['label' => ucfirst($civility)]);
        }
        $partner->setCivility($civility);

        //check last name first name
        if (is_null($apiRequest->getBodyRawParam('lastname'))) {
            $resp->setCode(400)
                ->setMessage('Parameter lastname is mandatory');
            return $resp;
        }
        $partner->setLastname($apiRequest->getBodyRawParam('firstname'));
        if (is_null($apiRequest->getBodyRawParam('firstname'))) {
            $resp->setCode(400)
                ->setMessage('Parameter firstname is mandatory');
            return $resp;
        }
        $partner->setFirstname($apiRequest->getBodyRawParam('firstname'));

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

        $parent = $this->tokenStorage->getToken()->getUser()->getPartner();
        $partner->setParent($parent)
            ->setAddress1($apiRequest->getBodyRawParam('address_1'))
            ->setAddress2($apiRequest->getBodyRawParam('address_2'))
            ->setZipCode($apiRequest->getBodyRawParam('zip_code'))
            ->setCountry($apiRequest->getBodyRawParam('city'))
            ->setCity($apiRequest->getBodyRawParam('country'))
            ->setPhone($apiRequest->getBodyRawParam('phone'))
            ->setPhone($apiRequest->getBodyRawParam('mobile'))
            ->setDeleted(Constant::NOT_DELETED)
            ->setSource($parent->getName());
        $partner->setHash(sha1(uniqid().date('YmdHis')));

        $this->entityManager->persist($partner);
        $this->entityManager->flush();

        //creating user api
        $partnerEvent = new PartnerEvent($partner);
        $this->dispatcher->dispatch($partnerEvent::PARTNER_CLIENT_ON_CREATE, $partnerEvent);

        return $resp;
    }
}
