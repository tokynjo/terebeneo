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

    public static $neobeNbLicense = [2, 5, 10];
    public static $neobeVolumeSize = [40, 100, 200];

    public static $dataMailList = [
        'partenaire_nom_societe' => 'Nom société partenaire',
        'client_nom_societe' => 'Nom société client',
        'client_nom' => 'Nom client',
        'client_prenom' => 'Prénom client',
        'client_civilite' => 'Civilité client',
        'client_civilite' => 'Civilité client',
        'client_nb_license' => 'Nombre de license client',
        'client_volume_total' => 'Volume total'
    ];
}
