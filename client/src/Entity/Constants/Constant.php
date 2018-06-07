<?php
namespace App\Entity\Constants;

class Constant
{
    const YES = 1;
    const NO = 0;
    const ENABLED = 1;
    const DISABLED = 0;


    public static $roles = ['ROLE_ADMIN' => 'Administrateur', 'ROLE_API' => 'Utilisateur API'];
}