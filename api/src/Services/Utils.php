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
     * Convert size file
     *
     * @param string $size
     *
     * @return string
     */
    public function getSizeFile($size)
    {
        $size = intval($size);
        if ($size >= 1048576) {
            return number_format(($size / 1048576), 2, '.', ' ')." Go";
        }
        if ($size >= 1024) {
            return number_format(($size / 1024), 2, '.', ' ')." Mo";
        } else {
            return $size." Ko";
        }
    }


    public function sendMailCreateUser($adress)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($adress);
        if (!$user) {
            $client = $this->container->get(ClientManager::SERVICE_NAME)->create($adress);
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setEmail($adress);
            $user->setCreatedIp(getenv('SERVER_ADDR'));
            $user->setConfirmationToken(md5($adress.time()));
            $password = substr(md5($user->getUsername()), 0, 10);
            $user->setPlainPassword($password);
            $user->setClient($client);
            $user->setUserName($user->getEmail());
            $userManager->updateUser($user);
            $userPref = $this->container->get(UserPreferenceManager::SERVICE_NAME)->create($user);
            if (!$user->getOsContainer()) {
                try {
                    $container = $this->objectStore->createContainer($user);
                    $user->setOsContainer($container->name);
                    $userManager->updateUser($user);
                } catch (\Exception $e) {

                }
            }
            $modelEMail = $this->container->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
                ['declenchement' => Constant::CREATE_USER, 'deletedAt' => null],
                ['id' => 'DESC'],
                1
            );
            $dataFrom['send_by'] = $modelEMail[0]->getEmitter();
            $template = $modelEMail[0]->getTemplate();
            $modele = ["__utilisateur__", "__password__"];
            $real = [$adress, $password];
            $template = str_replace($modele, $real, $template);
            $mailer = $this->mailer;

            return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template, $dataFrom);
        }
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
