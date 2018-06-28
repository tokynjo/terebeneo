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
    public function getId()
    {
        return $this->id;
    }

/**
 * @return mixed
 */
public function getType()
{
    return $this->type;
}

/**
 * @param mixed $type
 */
public function setType($type)
{
    $this->type = $type;
}

/**
 * @return string
 */
public function getContent()
{
    return $this->content;
}

/**
 * @param string $content
 */
public function setContent($content)
{
    $this->content = $content;
}

/**
 * @return mixed
 */
public function getNotification()
{
    return $this->notification;
}

/**
 * @param mixed $notification
 */
public function setNotification($notification)
{
    $this->notification = $notification;
}



    
}