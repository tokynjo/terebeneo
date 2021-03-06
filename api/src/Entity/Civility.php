<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Civility
 *
 * @ORM\Table(name="civility")
 * @ORM\Entity(repositoryClass="App\Repository\CivilityRepository")
 *
 */
class Civility
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
     * @ORM\Column(name="label", type="string", length=50)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="long_label", type="string", length=50)
     */
    private $longLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="rank", type="integer", length=2)
     */
    private $rank;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="civility", cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partner", mappedBy="civility", cascade={"persist"})
     */
    private $partners;

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
     * @return Civility
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * Set label
     *
     * @param string $label
     *
     * @return Civility
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getLongLabel()
    {
        return $this->longLabel;
    }

    /**
     * @param string $longLabel
     * @return Civility
     */
    public function setLongLabel($longLabel)
    {
        $this->longLabel = $longLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param string $rank
     * @return Civility
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->partners = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCivility($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCivility() === $this) {
                $user->setCivility(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partner[]
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->setCivility($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->contains($partner)) {
            $this->partners->removeElement($partner);
            // set the owning side to null (unless already changed)
            if ($partner->getCivility() === $this) {
                $partner->setCivility(null);
            }
        }

        return $this;
    }
}
