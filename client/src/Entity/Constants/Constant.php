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
    const NOTIF_CONFIRM_ACCOUNT_CREATION = 1;
    const NOTIF_USER_API_CREATION = 2;
    const NOTIF_NEOBE_ACCOUNT_CREATION = 3;

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 3;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    const PASSWORD_LENGTH = 8;

    const DEFAULT_CIVILITY = 1;
    const DEFAULT_CATEGORY = 1;

    const NOTIFICATION_TYPE_MAIL = 1;
    const NOTIFICATION_TYPE_SMS = 2;

    const STEP_ONE = 1;
    const STEP_TWO = 2;
    const STEP_THREE = 3;

    const DEFAULT_COLOR = '#ed6b39';

    public static $roles = ['ROLE_ADMIN' => 'Administrateur', 'ROLE_USER' => 'Utilisateur API'];
    public static $neobeNbLicense = [2, 5, 10];
    public static $neobeVolumeSize = [40, 100, 200];
    public static $dataMailList = [
        '__partenaire_nom_societe__' => 'Partenaire - Nom société',
        '__partenaire_nom__' => 'Partenaire - Nom',
        '__partenaire_prenom__' => 'Partenaire - prenom',
        '__partenaire_api_login__' => 'Partenaire - Api login',
        '__partenaire_api_mot_de_passe__' => 'Partenaire - Api mot de passe',
        '__partenaire_api_url' => 'Partenaire - Api url',
        '__client_nom_societe__' => 'Nom société client',
        '__client_nom__' => 'Nom client',
        '__client_prenom__' => 'Prénom client',
        '__client_civilite__' => 'Civilité client',
        '__client_nb_license__' => 'Nombre de license client',
        '__client_volume_total__' => 'Volume total',
        '__url_create_account_validation__' => 'Url de validation de création de compte',
        '__details_comptes_neobe__' => 'Details des comptes neobe',
        '__details_acces_neobe__' => 'Acces neobe'
    ];
}