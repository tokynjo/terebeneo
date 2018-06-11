<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 *
 */
class Notification
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
     * @var string
     *
     * @ORM\Column(name="status", type="text")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="NotificationContent", mappedBy="notification", cascade={"persist"})
     */
    private $notificationContents;


    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName()
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
    public function getDescription()
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Notification
     */
    public function setStatus(string $status): Notification
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getNotificationContents()
    {
        return $this->notificationContents;
    }

    /**
     * @param mixed $notificationContents
     * @return $this
     */
    public function setNotificationContents($notificationContents)
    {
        $this->notificationContents = $notificationContents;
        return $this;
    }


}