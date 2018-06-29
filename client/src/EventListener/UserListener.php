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
use App\Event\UserEvent;
use App\Services\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * user event Listener
 * Listen user event
 *
 * @package App\EventListener
 */
class UserListener
{
    private $entityManager;
    private $tokenStorage;
    private $translator;
    private $notificationService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param NotificationService $notificationService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        NotificationService $notificationService = null
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->notificationService = $notificationService;

        return $this;
    }


    /**
     * to execute on create a user api
     *
     * @param UserEvent $userEvent
     */
    public function onCreateUser(UserEvent $userEvent)
    {
        //sending login/password api email.
        $notification = $this->entityManager->getRepository('App:Notification')->find(Constant::NOTIF_USER_API_CREATION);
        $partner = $userEvent->getUser()->getPartner();
        $partner->setUser($userEvent->getUser());
        if($notification) {
            $this->notificationService->sendNotification ($partner, $notification);
        }

    }
}
