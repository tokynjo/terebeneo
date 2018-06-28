<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Neobe
 *
 * @ORM\Table(name="partner", options={"comment":"Table pour les comptes du partenaire "})
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
}