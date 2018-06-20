<?php
namespace App\Services;

use App\Entity\Constants\Constant;
use App\Entity\Notification;
use App\Entity\NotificationContent;
use App\Entity\Partner;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Society;
use AppBundle\Manager\ContactManager;
use AppBundle\Services\Rest\RestRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest\Request;
/**
 * Created by PhpStorm.
 * User: rnasolo@gmail.com
 * Date: 24/10/2017
 * Time: 10:39
 */
class NotificationService
{
    const SERVICE_NAME = 'api.notification_service';

    protected $em;
    protected $mailer;
    protected $sms;
    protected $template;


    public function __construct(EntityManagerInterface $entityManager, $mailer, $sms, $template)
    {
        $this->em = $entityManager;
        $this->template = $entityManager;
    }

    public function sendNotification (Partner $partner, Notification $notification)
    {
        foreach ($notification->getNotificationContents() as $content) {
            switch ($content->getType()) {
                case Constant::NOTIFICATION_TYPE_MAIL :
                    $template = $this->template->render(
                        'emails/creation-ter-account.html.twig', ['user' => $partner]
                    );
                    break;
                case Constant::NOTIFICATION_TYPE_SMS :
                    break;
            }
        }
    }

    protected function renderNotificationContent (NotificationContent $content, Partner $partner)
    {

        $template = $this->template->render(
            'emails/creation-ter-account.html.twig', ['user' => $partner]
        );
    }


}