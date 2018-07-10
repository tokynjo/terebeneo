<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Neobe
 *
 * @ORM\Table(name="neobe_account", options={"comment":"Table pour les comptes du partenaire "})
 * @ORM\Entity(repositoryClass="App\Repository\NeobeAccountRepository")
 *
 */
class NeobeAccount
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
     * @var integer
     *
     * @ORM\Column(name="installed", type="integer", length=2, nullable=true, options={"comment":" is installed"})
     */
    private $installed;

    /**
     * @var integer
     *
     * @ORM\Column(name="saved", type="integer", length=2, options={"comment":" has saved"}, nullable=true)
     */
    private $saved;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=250, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=250, nullable=true)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_size", type="integer", length=11, nullable=false)
     */
    private $totalSize;

    /**
     * @var string
     *
     * @ORM\Column(name="used_size", type="string", length=11, nullable=true)
     */
    private $usedSize;

    /**
     * @var datetime
     *
     * @ORM\Column(name="neobe_created_at", type="datetime", nullable=true)
     */
    private $neobeCreationDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="neobe_account_id", type="integer", length=11, nullable=false)
     */
    private $neobeAccountId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     */
    private $partner;


    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InstallSaveLog", mappedBy="neobeAccount", cascade={"persist"})
     */
    private $installSaveLog;
    
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
        $this->installSaveLog = new ArrayCollection();
    }

    /**
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstalled()
    {
        return $this->installed;
    }

    /**
     * @param $installed
     * @return $this
     */
    public function setInstalled($installed)
    {
        $this->installed = $installed;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return int
     */
    public function getNeobeAccountId()
    {
        return $this->neobeAccountId;
    }

    /**
     * @param $neobeAccountId
     * @return $this
     */
    public function setNeobeAccountId($neobeAccountId)
    {
        $this->neobeAccountId = $neobeAccountId;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getNeobeCreationDate()
    {
        return $this->neobeCreationDate;
    }

    /**
     * @param $neobeCreationDate
     * @return $this
     */
    public function setNeobeCreationDate($neobeCreationDate)
    {
        $this->neobeCreationDate = $neobeCreationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param $partner
     * @return $this
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
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
     * @return int
     */
    public function getSaved()
    {
        return $this->saved;
    }

    /**
     * @param $saved
     * @return $this
     */
    public function setSaved($saved)
    {
        $this->saved = $saved;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalSize()
    {
        return $this->totalSize;
    }

    /**
     * @param $totalSize
     * @return $this
     */
    public function setTotalSize($totalSize)
    {
        $this->totalSize = $totalSize;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsedSize()
    {
        return $this->usedSize;
    }

    /**
     * @param $usedSize
     * @return $this
     */
    public function setUsedSize($usedSize)
    {
        $this->usedSize = $usedSize;
        return $this;
    }

    /**
     * @return Collection|InstallSaveLog[]
     */
    public function getInstallSaveLog(): Collection
    {
        return $this->installSaveLog;
    }

    public function addInstallSaveLog(InstallSaveLog $installSaveLog): self
    {
        if (!$this->installSaveLog->contains($installSaveLog)) {
            $this->installSaveLog[] = $installSaveLog;
            $installSaveLog->setNeobeAccount($this);
        }

        return $this;
    }

    public function removeInstallSaveLog(InstallSaveLog $installSaveLog): self
    {
        if ($this->installSaveLog->contains($installSaveLog)) {
            $this->installSaveLog->removeElement($installSaveLog);
            // set the owning side to null (unless already changed)
            if ($installSaveLog->getNeobeAccount() === $this) {
                $installSaveLog->setNeobeAccount(null);
            }
        }

        return $this;
    }

}