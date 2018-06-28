<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification_type")
 * @ORM\Entity(repositoryClass="App\Repository\NotificationTypeRepository")
 *
 */
class NotificationType
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
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NotificationContent", mappedBy="type", cascade={"persist"})
     */
    private $notificationContents;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notificationContents = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Notification
     */
    public function setName(string $name): Notification
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Notification
     */
    public function setDescription(string $description): Notification
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection|NotificationContent[]
     */
    public function getNotificationContents(): Collection
    {
        return $this->notificationContents;
    }

    public function addNotificationContent(NotificationContent $notificationContent): self
    {
        if (!$this->notificationContents->contains($notificationContent)) {
            $this->notificationContents[] = $notificationContent;
            $notificationContent->setType($this);
        }

        return $this;
    }

    public function removeNotificationContent(NotificationContent $notificationContent): self
    {
        if ($this->notificationContents->contains($notificationContent)) {
            $this->notificationContents->removeElement($notificationContent);
            // set the owning side to null (unless already changed)
            if ($notificationContent->getType() === $this) {
                $notificationContent->setType(null);
            }
        }

        return $this;
    }

}