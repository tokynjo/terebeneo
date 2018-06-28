<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace App\EventListener;

use App\Entity\Constants\Constant;
use App\Entity\User;
use App\Event\PartnerEvent;
use App\Event\UserEvent;
use App\Services\NeobeApiService;
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
    private $mailer;
    private $fosUserManager;
    private $dispatcher;
    private $apiService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param null $mailer
     * @param null $fosUserManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param NeobeApiService $apiService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        $mailer = null,
        $fosUserManager = null,
        EventDispatcherInterface $eventDispatcher = null,
        NeobeApiService $apiService
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->mailer = $mailer;
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
        //var_dump($partner->getMail()); die;
        $user = new User();
        $user->setCivility($partner->getCivility())
            ->setEmail($partner->getMail())
            ->addRole(Constant::ROLE_USER)
            ->setEnabled(Constant::ENABLED)
            ->setDeleted(Constant::NO)
            ->setUsername($partner->getMail())
            ->setLastname($partner->getLastname())
            ->setFirstname($partner->getFirstname())
            ->setUserApi(Constant::YES);
        //password
        $pwdEncoder = new PasswordEncoder();
        $password = $pwdEncoder->random_str('alphanum', Constant::PASSWORD_LENGTH);
        $user->setPlainPassword($password);
        $this->fosUserManager->updateUser($user);
        $partner->setPassword($pwdEncoder->encode($password));
        $this->entityManager->persist($partner);
        $this->entityManager->flush();
        //sending email password/username
        $user->setPlainPassword($password);
        $userEvent = new UserEvent($user);
        $this->dispatcher->dispatch($userEvent::USER_ON_CREATE, $userEvent);
    }


    public function onStep1Validation (PartnerEvent $partnerEvent)
    {
        $partner = $partnerEvent->getPartner();

        $this->apiService->createNeobeAccount(
            $partner,
            $partnerEvent->getPartner(),
            $partnerEvent->getVolumeParLicenceGo()
        );

    }
}
