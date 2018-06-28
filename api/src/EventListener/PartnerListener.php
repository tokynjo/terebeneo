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

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param NotificationService $notification
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        NotificationService $notification
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->notificationService = $notification;

        return $this;
    }


    /**
     * to do on create partner client
     * sending email confirmation to create neobe account
     * @param PartnerEvent $partnerEvent
     */
    public function onCreateClientTer(PartnerEvent $partnerEvent)
    {
        die('sdfdsff onCreateClientTer');
        //sending confirmation email.
        $notification = $this->entityManager->getRepository('App:Notification')->find(Constant::NOTIF_CONFIRM_ACCOUNT_CREATION);
        $partner = $partnerEvent->getPartner();
        if($notification) {
            $this->notificationService->sendNotification ($partner, $notification);
        }
    }
}
