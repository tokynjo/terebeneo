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
    const PARTNER_CLIENT_ON_CREATE = "neobe_ter.client.child.on.create";

    private $partner;


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
}