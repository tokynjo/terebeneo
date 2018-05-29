<?php
// api/src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="my_user")
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
     * @Groups({"user"})
     */
    protected $email;


    /**
     * @Groups({"user"})
     */
    protected $plainPassword;

    /**
     * @Groups({"user"})
     */
    protected $username;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userCreated", cascade={"persist"})
     * @ORM\JoinColumn(name="id_creator", referencedColumnName="id")
     */
    private $creator;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=false, options={"default" : ""})
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_phone_code", type="integer" , length=11, nullable=true)
     */
    private $countryPhoneCode;
    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string" ,nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string" ,nullable=false, options={"default" : "1"})
     */
    private $civility = 1;
    /**
     * @var integer
     * @ORM\Column(name="is_deleted", type="integer" , length=4, nullable=false, options={"default" : "0"})
     */
    private $isDeleted = 0;

    /**
     * @var integer
     * @ORM\Column(name="is_external", type="integer" , length=4, nullable=false, options={"default" : "0"})
     */
    private $isExternal = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credit", type="integer" , length=4, nullable=false, options={"default":0})
     */
    private $nbCredit;

    /**
     * @var integer
     * @ORM\Column(name="mailing_actu", type="integer", length=1, options={"default":0})
     */
    private $mailingActu;

    /**
     * @var integer
     * @ORM\Column(name="mailing_promo", type="integer", length=1, options={"default":0})
     */
    private $mailingPromo;

    /**
     * @var integer
     * @ORM\Column(name="mailing_neobe" ,type="integer", length=1, options={"default":0})
     */
    private $mailingNeobe;
    /**
     * @var integer
     * @ORM\Column(name="status", type="integer", length=1, options={"default":0})
     */
    private $status;
    /**
     * @var integer
     * @ORM\Column(name="id_bu", type="integer", nullable=true)
     */
    private $idBu;

    /**
     * @var integer
     * @ORM\Column(name="id_lang", type="integer", nullable=true)
     */
    private $lang;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_login", type="datetime", nullable=true)
     */
    private $firstLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="signup_token", type="string" ,nullable=true)
     */
    private $signUpToken;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;
    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", nullable=true)
     */
    private $origin;
    /**
     * @var \Datetime
     * @ORM\Column(name="created_at" ,type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;
    //    /**
    //     * @var \Datetime
    //     * @ORM\Column(name="last_login_at" ,type="datetime")
    //     */
    //    private $lastLoginAt;
    /**
     * @var string
     * @ORM\Column(name="created_ip" ,type="string", length=100, options={"default" : 0})
     */
    private $createdIp;
    /**
     * @var string
     * @ORM\Column(name="last_ip" ,type="string", length=100, nullable=true)
     */
    private $lastIp;

    /**
     * @var string
     * @ORM\Column(name="os_container" ,type="string", length=250, nullable=true)
     */
    private $osContainer;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="user", cascade={"persist"})
     */
    private $news;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvitationRequest", mappedBy="from", cascade={"persist"})
     */
    private $invitationsSent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectUser" , mappedBy="user" , cascade={"all"})
     */
    private $projectsUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPreference" , mappedBy="user" , cascade={"all"})
     */
    private $preferences;

    /**
     * File created by user
     *
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="user", cascade={"persist"})
     */

    private $files;

    /**
     * Folder created by user
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Folder", mappedBy="user", cascade={"persist"})
     */

    private $folders;

    /**
     * Folder created for another user
     *
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="createdBy", cascade={"persist"})
     */
    private $myFolders;

    /**
     *
     * @ORM\OneToMany(targetEntity="FolderLog", mappedBy="user", cascade={"persist"})
     */
    private $folderLogs;

    /**
     *
     * @ORM\OneToMany(targetEntity="FileLog", mappedBy="user", cascade={"persist"})
     */
    private $fileLogs;

    /**
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade={"persist"})
     */
    private $comments;

    /**
     * folders shared with the users
     *
     * @ORM\OneToMany(targetEntity="FolderUser", mappedBy="user", cascade={"persist"})
     */
    private $foldersUser;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="creator")
     */
    private $userCreated;

    /**
     * @ORM\OneToMany(targetEntity="Log", mappedBy="user", cascade={"persist"})
     */
    private $logs;

    /**
     * files shared with the users
     *
     * @ORM\OneToMany(targetEntity="FileUser", mappedBy="user", cascade={"persist"})
     */
    private $filesUser;

    /**
     * config mail
     *
     * @ORM\OneToMany(targetEntity="EmailAutomatique", mappedBy="emitter", cascade={"persist"})
     */
    private $mailConfig;

    /**
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="deleteBy", cascade={"persist"})
     */
    private $commentDelete;

    /**
     * @ORM\OneToMany(targetEntity="Upload", mappedBy="user", cascade={"persist"})
     */
    private $uploads;

    /**
     * constructor
     *
     * @return User
     */
    public function __construct()
    {
        parent::__construct();

        $this->news = new ArrayCollection();
        $this->invitationsSent = new ArrayCollection();
        $this->projectsUser = new ArrayCollection();
        $this->preferences = new ArrayCollection();
        $this->hash = 0;
        $this->nbCredit = 0;
        $this->mailingActu = $this->mailingPromo = $this->mailingNeobe = $this->status = $this->lastIp = 0;
        $this->createdAt = new \DateTime();

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

    /**
     * @return mixed
     */
    public function getCommentDelete()
    {
        return $this->commentDelete;
    }

    /**
     * @param mixed $commentDelete
     */
    public function setCommentDelete($commentDelete)
    {
        $this->commentDelete = $commentDelete;
    }
    public function getData(){
        return [
            "id" => $this->id,
            "name" => $this->username,
            "firstname" =>$this->firstname,
            "lastname" => $this->lastname,
            "lastname" => $this->lastname,
            "email" => $this->email,
        ];
    }

    /**
     * @return mixed
     */
    public function getUploads()
    {
        return $this->uploads;
    }

    /**
     * @param mixed $uploads
     */
    public function setUploads($uploads)
    {
        $this->uploads = $uploads;
    }


}