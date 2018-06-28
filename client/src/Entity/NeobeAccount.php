<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var string
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

    private $neobeCreationDate;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="neobeAccounts", cascade={"persist"})
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
     * @var integer
     *
     * @ORM\Column(name="id_account", type="integer", length=2, nullable=true)
     */
    private $idAccount;

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
    }

    /**
     * @return int
     */
    public function getIdAccount()
    {
        return $this->idAccount;
    }

    /**
     * @param int $idAccount
     */
    public function setIdAccount($idAccount)
    {
        $this->idAccount = $idAccount;
    }

    public function getInstalled(): ?int
    {
        return $this->installed;
    }

    public function setInstalled(?int $installed): self
    {
        $this->installed = $installed;

        return $this;
    }

    public function getSaved(): ?int
    {
        return $this->saved;
    }

    public function setSaved(?int $saved): self
    {
        $this->saved = $saved;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTotalSize(): ?int
    {
        return $this->totalSize;
    }

    public function setTotalSize(int $totalSize): self
    {
        $this->totalSize = $totalSize;

        return $this;
    }

    public function getUsedSize(): ?string
    {
        return $this->usedSize;
    }

    public function setUsedSize(?string $usedSize): self
    {
        $this->usedSize = $usedSize;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): self
    {
        $this->partner = $partner;

        return $this;
    }


}