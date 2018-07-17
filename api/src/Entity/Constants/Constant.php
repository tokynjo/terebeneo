<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:10
 */
namespace App\Entity\Constants;

final class Constant
{
    const DEFAULT_CIVILITY = 1;
    const NOT_DELETED = 0;
    const DELETED = 1;
    const NOTIF_CONFIRM_ACCOUNT_CREATION = 1;

    const NOTIFICATION_TYPE_MAIL = 1;
    const NOTIFICATION_TYPE_SMS = 2;

    public static $neobeNbLicense = [2, 5, 10];
    public static $neobeNbLicenseSelect = [2 => 2, 5 => 5, 10 => 10];
    public static $neobeVolumeSize = [40, 100, 200];
    public static $neobeVolumeSizeSelect = [40 => 40, 100 => 100, 200 => 200];
    public static $partnerCategory = [1, 2];
    public static $partnerCivility = ['M', 'Mlle', 'Mme'];

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
