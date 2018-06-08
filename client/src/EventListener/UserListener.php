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
 * File event Listener
 * Listen file event
 *
 * @package App\EventListener
 */
class UserListener
{
    private $em;
    private $tokenStorage;
    private $translator;
    private $mailer;

    /**
     * UserListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param null $mailer
     * @param null template
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        $mailer = null
        //$template = null
    ) {
        $this->em = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->mailer = $mailer;


        return $this;
    }


    /**
     * to execute on delete file
     *
     * @param UserEvent $userEvent
     */
    public function onCreateUser(UserEvent $userEvent)
    {
        $container = $GLOBALS['kernel']->getContainer();
        $data = new \StdClass();
        $data->firstname =  $userEvent->getUser()->getFirstname();
        $data->lastname =  $userEvent->getUser()->getLastname();
        $data->email =  $userEvent->getUser()->getEmail();
        //$template = $this->template->render('admin/user/email/creation.html.twig', $userEvent->getUser());
        $template = $container->get('templating')->render('admin/user/email/creation.html.twig', $userEvent->getUser());
        $this->idMail = $this->mailer->sendMailGrid('Création de compte', $userEvent->getEmail(), $template, null);

    }
}
