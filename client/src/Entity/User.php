<?php
// api/src/Entity/User.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /**
     * constructor
     *
     * @return User
     */
    public function __construct()
    {
        parent::__construct();
        return $this;
    }

    public function isUser(?UserInterface $user = null): bool
    {
        return $user instanceof self && $user->id === $this->id;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return int
     */
    public function getCountryPhoneCode()
    {
        return $this->countryPhoneCode;
    }

    /**
     * @param int $countryPhoneCode
     */
    public function setCountryPhoneCode($countryPhoneCode)
    {
        $this->countryPhoneCode = $countryPhoneCode;
    }

    /**
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param string $civility
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
    }

    /**
     * @return \DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * @param \DateTime $firstLogin
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getCreatedIp()
    {
        return $this->createdIp;
    }

    /**
     * @param string $createdIp
     */
    public function setCreatedIp($createdIp)
    {
        $this->createdIp = $createdIp;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param int $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return int
     */
    public function getIsExternal()
    {
        return $this->isExternal;
    }

    /**
     * @param int $isExternal
     */
    public function setIsExternal($isExternal)
    {
        $this->isExternal = $isExternal;
    }

    /**
     * @return int
     */
    public function getIdBu()
    {
        return $this->idBu;
    }

    /**
     * @param int $idBu
     */
    public function setIdBu($idBu)
    {
        $this->idBu = $idBu;
    }

    /**
     * @return int
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param int $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \Datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * @param string $lastIp
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;
    }

    /**
     * @return mixed
     */
    public function getInvitationsSent()
    {
        return $this->invitationsSent;
    }

    /**
     * @param mixed $invitationsSent
     */
    public function setInvitationsSent($invitationsSent)
    {
        $this->invitationsSent = $invitationsSent;
    }

    /**
     * @return mixed
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param mixed $folders
     */
    public function setFolders($folders)
    {
        $this->folders = $folders;
    }

    /**
     * @return mixed
     */
    public function getFolderLogs()
    {
        return $this->folderLogs;
    }

    /**
     * @param mixed $folderLogs
     */
    public function setFolderLogs($folderLogs)
    {
        $this->folderLogs = $folderLogs;
    }

    /**
     * @return mixed
     */
    public function getFileLogs()
    {
        return $this->fileLogs;
    }

    /**
     * @param mixed $fileLogs
     */
    public function setFileLogs($fileLogs)
    {
        $this->fileLogs = $fileLogs;
    }

    /**
     * @return mixed
     */
    public function getFoldersUser()
    {
        return $this->foldersUser;
    }

    /**
     * @param mixed $foldersUser
     */
    public function setFoldersUser($foldersUser)
    {
        $this->foldersUser = $foldersUser;
    }

    /**
     * @return mixed
     */
    public function getFilesUser()
    {
        return $this->filesUser;
    }

    /**
     * @param mixed $filesUser
     */
    public function setFilesUser($filesUser)
    {
        $this->filesUser = $filesUser;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return int
     */
    public function getNbCredit()
    {
        return $this->nbCredit;
    }

    /**
     * @param int $nbCredit
     */
    public function setNbCredit($nbCredit)
    {
        $this->nbCredit = $nbCredit;
    }

    /**
     * @return int
     */
    public function getMailingActu()
    {
        return $this->mailingActu;
    }

    /**
     * @param int $mailingActu
     */
    public function setMailingActu($mailingActu)
    {
        $this->mailingActu = $mailingActu;
    }

    /**
     * @return int
     */
    public function getMailingPromo()
    {
        return $this->mailingPromo;
    }

    /**
     * @param int $mailingPromo
     */
    public function setMailingPromo($mailingPromo)
    {
        $this->mailingPromo = $mailingPromo;
    }

    /**
     * @return int
     */
    public function getMailingNeobe()
    {
        return $this->mailingNeobe;
    }

    /**
     * @param int $mailingNeobe
     */
    public function setMailingNeobe($mailingNeobe)
    {
        $this->mailingNeobe = $mailingNeobe;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getSignUpToken()
    {
        return $this->signUpToken;
    }

    /**
     * @param string $signUpToken
     */
    public function setSignUpToken($signUpToken)
    {
        $this->signUpToken = $signUpToken;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getOsContainer()
    {
        return $this->osContainer;
    }

    /**
     * @param string $osContainer
     */
    public function setOsContainer($osContainer)
    {
        $this->osContainer = $osContainer;
    }

    /**
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @return mixed
     */
    public function getProjectsUser()
    {
        return $this->projectsUser;
    }

    /**
     * @param mixed $projectsUser
     */
    public function setProjectsUser($projectsUser)
    {
        $this->projectsUser = $projectsUser;
    }

    /**
     * @return mixed
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

    /**
     * @param mixed $preferences
     */
    public function setPreferences($preferences)
    {
        $this->preferences = $preferences;
    }

    /**
     * @return mixed
     */
    public function getMyFolders()
    {
        return $this->myFolders;
    }

    /**
     * @param mixed $myFolders
     */
    public function setMyFolders($myFolders)
    {
        $this->myFolders = $myFolders;
    }

    /**
     * @return mixed
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * @param mixed $userCreated
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return mixed
     */
    public function getMailConfig()
    {
        return $this->mailConfig;
    }

    /**
     * @param mixed $mailConfig
     */
    public function setMailConfig($mailConfig)
    {
        $this->mailConfig = $mailConfig;
    }

    /**
     * @return string
     */
    public function getInfosUser()
    {
        return $this->getFirstname()." ".$this->getLastname();
    }

    public function getViewDefault()
    {
        if (sizeof($this->preferences) > 0) {
            return $this->preferences[0];
        } else {
            return null;
        }
    }
}