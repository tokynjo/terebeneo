<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification content
 *
 * @ORM\Table(name="notification_content")
 * @ORM\Entity(repositoryClass="App\Repository\NotificationContentRepository")
 *
 */
class NotificationContent
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
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="notificationContents", cascade={"persist"})
     * @ORM\JoinColumn(name="id_notification", referencedColumnName="id")
     */
    private $notification;
    /**
     *
     * @ORM\ManyToOne(targetEntity="NotificationType", inversedBy="notificationContents", cascade={"persist"})
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     */
    private $type;


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
    
}