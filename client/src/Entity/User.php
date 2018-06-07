<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @var string
     *
     * @ORM\Column(name="mobile", type="string" ,nullable=true)
     */
    private $mobile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="civility", referencedColumnName="id")
     */
    private $civility ;


    /**
     * @var \Datetime
     * @ORM\Column(name="created_at" ,type="datetime", nullable=true)

     */
    private $createdAt;

    /**
     * @var \Datetime
     * @ORM\Column(name="updated_at" ,type="datetime", nullable=true)

     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="user_api", type="integer", length=2, nullable=true)
     */
    private $userApi;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="integer", length=2, nullable=true)
     */
    private $deleted;

    /**
     * @return mixed
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param mixed $civility
     * @return User
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }

    /**
     * @param \Datetime $createdAt
     */
    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;
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
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
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
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
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
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
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
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return \Datetime
     */
    public function getUpdatedAt(): \Datetime
    {
        return $this->updatedAt;
    }

    /**
     * @param \Datetime $updatedAt
     */
    public function setUpdatedAt(\Datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getUserApi()
    {
        return $this->userApi;
    }

    /**
     * @param string $userApi
     */
    public function setUserApi(string $userApi): User
    {
        $this->userApi = $userApi;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     * @return User
     */
    public function setDeleted(string $deleted): User
    {
        $this->deleted = $deleted;
        return $this;
    }


}