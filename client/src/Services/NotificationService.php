<?php
namespace App\Services;

use App\Entity\Constants\Constant;
use App\Entity\Notification;
use App\Entity\Partner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest\Request;

/**
 * Class NotificationService
 * @package App\Services
 */
class NotificationService
{
    const SERVICE_NAME = 'app.notification_service';

    protected $em;
    protected $template;
    protected $mailer;
    protected $sms;
    protected $frontUrl;

    public function __construct(EntityManagerInterface $entityManager,$template, $mailer, $sms = null, $frontUrl )
    {
        $this->em = $entityManager;
        $this->template = $template;
        $this->mailer = $mailer;
        $this->frontUrl = $frontUrl;
    }

    public function sendNotification (Partner $partner, Notification $notification)
    {

        foreach ($notification->getNotificationContents() as $content) {
            switch ($content->getType()->getId()) {
                case Constant::NOTIFICATION_TYPE_MAIL :
                    $header = '';
                    $footer = '';
                    $mailContent = $content->getContent();
                    $parent = $partner->getParent();
                    if ($parent->getHeadersFooters()){
                        $header = $parent->getActiveHeadersFooters()->getHeader() ? $parent->getActiveHeadersFooters()->getHeader() : '';
                        $footer = $parent->getActiveHeadersFooters()->getFooter() ? $parent->getActiveHeadersFooters()->getfooter() : '';
                    }
                    $header = $this->replaceDataVars($partner, $header);
                    $footer = $this->replaceDataVars($partner, $footer);
                    $mailContent = $this->replaceDataVars($partner, $mailContent);

                    $template = $this->template->render(
                        'emails/creation-ter-account.html.twig',
                        [
                            'header' => $header,
                            'content' => $mailContent,
                            'footer' => $footer
                        ]
                    );
                    $this->mailer->sendMailGrid(
                        'Confirmation de création de compte Neobe',
                        [$partner->getMail()],
                        $template,
                        []
                    );
                    break;
                case Constant::NOTIFICATION_TYPE_SMS :
                    $sms = $this->replaceDataVars($partner, $content->getContent());
                    break;
            }
        }
    }

    /**
     * replace variable to this value from the bdd
     * @param Partner $partner
     * @param $content
     * @return mixed
     */
    protected function replaceDataVars(Partner $partner, $content)
    {
        foreach(Constant::$dataMailList as $key => $label) {
            switch ($key) {
                case '__partenaire_nom_societe__' :
                    $content = str_replace($key, $partner->getParent()->getName(), $content);
                    break;
                case '__client_nom__' :
                    $content = str_replace($key, $partner->getLastname(), $content);
                    break;
                case '__client_prenom__' :
                    $content = str_replace($key, $partner->getFirstname(), $content);
                    break;
                case '__client_civilite__' :
                    $content = str_replace($key, $partner->getCivility()->getLabel(), $content);
                    break;
                case '__client_nb_license__' :
                    $content = str_replace($key, $partner->getNbLicense(), $content);
                    break;
                case '__client_volume_total__' :
                    $content = str_replace($key, $partner->getVolumeSize(), $content);
                    break;
                case '__url_create_account_validation__' :
                    $frontUrl = $this->frontUrl.'/'.$partner->getHash();
                    $content = str_replace($key, $frontUrl, $content);
                    break;
            }
        }

        return $content;
    }


}