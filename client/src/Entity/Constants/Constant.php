<?php
namespace App\Entity\Constants;

class Constant
{
    const YES = 1;
    const NO = 0;
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETED_SALT = "-deleted-";

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 3;



    public static $roles = ['ROLE_ADMIN' => 'Administrateur', 'ROLE_USER' => 'Utilisateur API'];
}