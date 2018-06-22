<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Partner
 *
 * @ORM\Table(name="partner", options={"comment":"Table pour les partenaires client "})
 * @ORM\Entity(repositoryClass="App\Repository\PartnerRepository")
 *
 */
class Partner
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, options={"comment":"Society name "})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=250, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=250, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=250, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=250, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=10, nullable=true)
     */
    private $zipCode;


    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=250, nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="integer", length=2, nullable=true)
     */
    private $category;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer", length=2, nullable=true)
     */
    private $type;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility", inversedBy="partners", cascade={"persist"})
     * @ORM\JoinColumn(name="civility", referencedColumnName="id")
     */
    private $civility ;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string" ,nullable=true)
     */
    private $mail;
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
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="integer", length=10, nullable=true)
     */
    private $status;
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_license", type="integer", length=5, nullable=true)
     */
    private $nbLicense;

    /**
     * @var integer
     *
     * @ORM\Column(name="volume_size", type="integer", length=5, nullable=true)
     */
    private $volumeSize;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="integer", length=2)
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partner", mappedBy="parent", cascade={"persist"})
     */
    private $children;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HeaderFooter", mappedBy="partner", cascade={"persist"})
     */
    private $headersFooters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ValidationLog", mappedBy="partner", cascade={"persist"})
     */
    private $validation;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accounts = new  ArrayCollection();
        $this->headersFooters = new  ArrayCollection();
        $this->children = new ArrayCollection();
        $this->validation = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param $address1
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
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
     * @param $deleted
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param mixed $accounts
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeadersFooters()
    {
        return $this->headersFooters;
    }

    /**
     * @param $headersFooters
     * @return $this
     */
    public function setHeadersFooters($headersFooters)
    {
        $this->headersFooters = $headersFooters;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param $civility
     * @return $this
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param $mobile
     * @return $this
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param $zipCode
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getNbLicense()
    {
        return $this->nbLicense;
    }

    /**
     * @param int $nbLicense
     */
    public function setNbLicense($nbLicense)
    {
        $this->nbLicense = $nbLicense;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getVolumeSize()
    {
        return $this->volumeSize;
    }

    /**
     * @param int $volumeSize
     */
    public function setVolumeSize($volumeSize)
    {
        $this->volumeSize = $volumeSize;
    }

    public function addChild(Partner $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Partner $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function addHeadersFooter(HeaderFooter $headersFooter): self
    {
        if (!$this->headersFooters->contains($headersFooter)) {
            $this->headersFooters[] = $headersFooter;
            $headersFooter->setPartner($this);
        }

        return $this;
    }

    public function removeHeadersFooter(HeaderFooter $headersFooter): self
    {
        if ($this->headersFooters->contains($headersFooter)) {
            $this->headersFooters->removeElement($headersFooter);
            // set the owning side to null (unless already changed)
            if ($headersFooter->getPartner() === $this) {
                $headersFooter->setPartner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ValidationLog[]
     */
    public function getValidation(): Collection
    {
        return $this->validation;
    }

    public function addValidation(ValidationLog $validation): self
    {
        if (!$this->validation->contains($validation)) {
            $this->validation[] = $validation;
            $validation->setPartner($this);
        }

        return $this;
    }

    public function removeValidation(ValidationLog $validation): self
    {
        if ($this->validation->contains($validation)) {
            $this->validation->removeElement($validation);
            // set the owning side to null (unless already changed)
            if ($validation->getPartner() === $this) {
                $validation->setPartner(null);
            }
        }

        return $this;
    }

}