<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace App\EventListener;

use App\Entity\Constants\Constant;
use App\Entity\NeobeAccount;
use App\Entity\User;
use App\Event\PartnerEvent;
use App\Event\UserEvent;
use App\Services\NeobeApiService;
use App\Services\NotificationService;
use App\Services\PasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Partner event Listener
 * Listen partner event
 *
 * @package App\EventListener
 */
class PartnerListener
{
    private $entityManager;
    private $tokenStorage;
    private $translator;
    private $notificationService;
    private $fosUserManager;
    private $dispatcher;
    private $apiService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param null $notificationService
     * @param null $fosUserManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param NeobeApiService $apiService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        NotificationService $notificationService = null,
        $fosUserManager = null,
        EventDispatcherInterface $eventDispatcher = null,
        NeobeApiService $apiService
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->notificationService = $notificationService;
        $this->fosUserManager = $fosUserManager;
        $this->dispatcher = $eventDispatcher;
        $this->apiService = $apiService;

        return $this;
    }


    /**
     * to do on create partner client
     * create api account
     * @param PartnerEvent $partnerEvent
     */
    public function onCreatePartnerClient(PartnerEvent $partnerEvent)
    {
        $partner = $partnerEvent->getPartner();
        $user = new User();
        $user->setCivility($partner->getCivility())
            ->setEmail($partner->getMail())
            ->addRole(Constant::ROLE_USER)
            ->setEnabled(Constant::ENABLED)
            ->setDeleted(Constant::NO)
            ->setUsername($partner->getMail())
            ->setLastname($partner->getLastname())
            ->setFirstname($partner->getFirstname())
            ->setUserApi(Constant::YES)
            ->setPartner($partner);
        //password
        $pwdEncoder = new PasswordEncoder();
        $password = $pwdEncoder->random_str('alphanum', Constant::PASSWORD_LENGTH);
        $user->setPlainPassword($password);
        $partner->setPassword($pwdEncoder->encode($password, $partner->getHash()));
        $this->fosUserManager->updateUser($user);
        $this->entityManager->persist($partner);
        $this->entityManager->flush();

        //sending email password/username
        $user->setPlainPassword($password);
        $userEvent = new UserEvent($user);
        $this->dispatcher->dispatch($userEvent::USER_ON_CREATE, $userEvent);
    }


    /**
     * to do on client validation create account.
     * post from step 1
     * @param PartnerEvent $partnerEvent
     */
    public function onValidateNeobeAccountCreation (PartnerEvent $partnerEvent)
    {
        $partner = $partnerEvent->getPartner();
        $response = $this->apiService->createNeobeAccount(
            $partner,
            $partnerEvent->getNbLicencesToCreate(),
            $partnerEvent->getVolumeParLicenceGo()
        );
        if($response->getCode() == NeobeApiService::CODE_SUCCESS) {
            //maj client details
            $partner->setNeobeAccountId($response->getData()->id_client);
            $pwdEncoder = new PasswordEncoder();
            $partner->setNeobePassword($pwdEncoder->encode($response->getData()->password, $partner->getHash()));
            $partner->setNeobeCreatedAt(
                    \DateTime::createFromFormat('m/d/Y H:i:s', $response->getData()->created_at)
                );
            $this->entityManager->persist($partner);
            $this->entityManager->flush();
            //create account
            if(isset($response->getData()->compte) && sizeof($response->getData()->compte) >0) {
                foreach($response->getData()->compte as $a ) {
                    $account = new NeobeAccount();
                    $account->setNeobeAccountId($a->id)
                        ->setLogin($a->login)
                        ->setPassword($pwdEncoder->encode($a->password, $partner->getHash()))
                        ->setTotalSize($a->espace_total_mo)
                        ->setUsedSize($a->espace_utilise_mo)
                        ->setNeobeCreationDate(
                            \DateTime::createFromFormat('m/d/Y H:i:s', $a->creat_at))
                        ->setPartner($partner);
                $this->entityManager->persist($account);
                $this->entityManager->flush();
                }
            }

            //send notification mail
            $notification = $this->entityManager->getRepository('App:Notification')->find(Constant::NOTIF_NEOBE_ACCOUNT_CREATION);
            $this->notificationService->sendNotification($partner, $notification);
        }
    }

    public function onEditPartner (PartnerEvent $partnerEvent)
    {
        $partner = $partnerEvent->getPartner();
        if (is_null($partner->getParent())) {
            //if partner with user Api (update email/username)
            $user = $partner->getUser()[0];
            $user->setUsername($partner->getMail())
                ->setUsernameCanonical($partner->getMail())
                ->setEmail($partner->getMail())
                ->setEmailCanonical($partner->getMail());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /** Send access api of partener by email
     * @param PartnerEvent $partnerEvent
     */
    public function onSendPassword(PartnerEvent $partnerEvent){
        $partner = $partnerEvent->getPartner();
        $notification = $this->entityManager->getRepository('App:Notification')->find(Constant::NOTIF_USER_API_RESEND);
        $this->notificationService->sendNotification($partner, $notification);
        if($notification) {
            $this->notificationService->sendNotification ($partner, $notification);
        }

    }
}
