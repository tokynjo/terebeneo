<?php

namespace App\Services;

use App\Entity\Api\ApiResponse;
use App\Entity\Client;
use App\Entity\Constants\Constant;
use App\Manager\ClientManager;
use App\Manager\EmailAutomatiqueManager;
use App\Manager\FileManager;
use App\Manager\FileUserManager;
use App\Manager\UserPreferenceManager;
use App\Services\OpenStack\ObjectStore;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;

class Utils
{

    private $container;
    private $mailer;
    private $em;
    protected $objectStore = null;

    /**
     * Permalink constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em,
                                Mailer $mailer, ObjectStore $objectStore)
    {
        $this->container = $container;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->objectStore = $objectStore;
    }


    /**
     * To validate a mail format
     *
     * @param string $mail
     * @return mixed
     */
    public function validateEmail($mail = "")
    {
        $emailConstraint = new Email();

        return $this->container->get('validator')->validate(
            $mail,
            $emailConstraint
        );
    }

    /**
     * verify if ssl is activate on server
     * @return bool
     */
    public static function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        
        return false;
    }

}
