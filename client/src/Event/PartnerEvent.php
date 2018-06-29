<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:26
 */
namespace App\Event;

use App\Entity\Partner;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PartnerEvent
 *
 * @package App\Event
 */
class PartnerEvent extends Event
{
    const PARTNER_CLIENT_ON_CREATE = "partner.client.on.create";
    const PARTNER_CLIENT_ON_VALIDATE_ACCOUNT = "partner.client.on.validate.account";

    private $partner;
    private $nb_licences_to_create = null;
    private $volume_par_licence_Go = null;


    /**
     * @param Partner $partner
     */
    public function __construct(Partner $partner)
    {
        $this->partner = $partner;
    }

    /**
     * @return Partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param $partner
     * @return $this
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
        return $this;
    }

    /**
     * @return null
     */
    public function getNbLicencesToCreate()
    {
        return $this->nb_licences_to_create;
    }

    /**
     * @param $nb_licences_to_create
     * @return $this
     */
    public function setNbLicencesToCreate($nb_licences_to_create)
    {
        $this->nb_licences_to_create = $nb_licences_to_create;
        return $this;
    }

    /**
     * @return null
     */
    public function getVolumeParLicenceGo()
    {
        return $this->volume_par_licence_Go;
    }

    /**
     * @param $volume_par_licence_Go
     * @return $this
     */
    public function setVolumeParLicenceGo($volume_par_licence_Go)
    {
        $this->volume_par_licence_Go = $volume_par_licence_Go;
        return $this;
    }


}