<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ValidationLogRepository")
 */
class InstallSaveLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var \Datetime
     * @ORM\Column(name="created_at" ,type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NeobeAccount", inversedBy="installSaveLog", cascade={"persist"})
     * @ORM\JoinColumn(name="neobe_account_id", referencedColumnName="id")
     */
    private $neobeAccount;

    public function getId()
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function getNeobeAccount(): ?NeobeAccount
    {
        return $this->neobeAccount;
    }

    public function setNeobeAccount(?NeobeAccount $neobeAccount): self
    {
        $this->neobeAccount = $neobeAccount;

        return $this;
    }


}
