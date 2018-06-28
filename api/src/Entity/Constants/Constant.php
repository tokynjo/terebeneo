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
    public static $neobeVolumeSize = [40, 100, 200];
    public static $partnerCategory = [1, 2];
    public static $partnerCivility = ['M', 'Mlle', 'Mme'];

    public static $dataMailList = [
        '__partenaire_nom_societe__' => 'Nom société partenaire',
        '__client_nom_societe__' => 'Nom société client',
        '__client_nom__' => 'Nom client',
        '__client_prenom__' => 'Prénom client',
        '__client_civilite__' => 'Civilité client',
        '__client_nb_license__' => 'Nombre de license client',
        '__client_volume_total__' => 'Volume total',
        '__url_create_account_validation__' => 'Url validation de création de compte'
    ];


}
