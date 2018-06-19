<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:26
 */
namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEvent
 *
 * @package App\Event
 */
class UserEvent extends Event
{
    const USER_ON_CREATE = "user.on.create";

    private $user;


    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserEvent
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
}
