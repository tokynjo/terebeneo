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
    private $template;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param null $mailer
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
     * to do on create partner client
     * sending email confirmation to create neobe account
     * @param PartnerEvent $partnerEvent
     */
    public function onCreateClientTer(PartnerEvent $partnerEvent)
    {
        //sending confirmation email.
        $partner = $partnerEvent->getPartner();
        $template = $this->template->render(
            'emails/creation-ter-account.html.twig', ['user' => $partner]
        );
        print_r($template); die;
        $this->idMail = $this->mailer->sendMailGrid(
            'Création de compte ',
            [$partner->getMail()],
            $template,
            null
        );

    }
}
