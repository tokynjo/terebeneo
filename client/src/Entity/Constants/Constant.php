<?php
namespace App\Entity\Constants;

class Constant
{

    const YES = 1;
    const NO = 0;

    const DELETED = 1;
    const NOT_DELETED = 0;
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETED_SALT = "-deleted-";

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 3;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    const PASSWORD_LENGTH = 8;

    const DEFAULT_CIVILITY = 1;

    const NOTIFICATION_TYPE_MAIL = 1;
    const NOTIFICATION_TYPE_SMS = 2;

    const STEP_ONE = 1;
    const STEP_TWO = 2;
    const STEP_THREE = 3;

    public static $roles = ['ROLE_ADMIN' => 'Administrateur', 'ROLE_USER' => 'Utilisateur API'];
    public static $neobeNbLicense = [2, 5, 10];
    public static $neobeVolumeSize = [40, 100, 200];
    public static $dataMailList = [
        '__partenaire_nom_societe__' => 'Nom société partenaire',
        '__client_nom_societe__' => 'Nom société client',
        '__client_nom__' => 'Nom client',
        '__client_prenom__' => 'Prénom client',
        '__client_civilite__' => 'Civilité client',
        '__client_nb_license__' => 'Nombre de license client',
        '__client_volume_total__' => 'Volume total'
    ];
}