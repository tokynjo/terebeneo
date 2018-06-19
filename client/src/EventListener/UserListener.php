<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace App\EventListener;

use App\Entity\User;
use App\Event\UserEvent;
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
    private $mailer;
    private $template;

    /**
     * UserListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param null $mailer
     * @param EventDispatcherInterface $eventDispatcher,
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        $mailer = null,
        $template = null
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->template = $template;

        return $this;
    }


    /**
     * to execute on delete file
     *
     * @param UserEvent $userEvent
     */
    public function onCreateUser(UserEvent $userEvent)
    {
        $template = $this->template->render(
            'admin/user/email/creation.html.twig', ['user' => $userEvent->getUser()]
        );
        $this->idMail = $this->mailer->sendMailGrid(
            'Création de compte ',
            [$userEvent->getUser()->getEmail()],
            $template,
            null
        );

    }
}
