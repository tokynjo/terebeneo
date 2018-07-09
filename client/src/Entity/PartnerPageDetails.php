<?php
namespace App\Entity;

use App\Entity\Constants\Constant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Partner
 *
 * @ORM\Table(name="partner_page_details", options={"comment":"Table pour les details des contenus des pages partenaire  "})
 * @ORM\Entity(repositoryClass="App\Repository\PartnerPageDetailsRepository")
 *
 */
class PartnerPageDetails
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", length=250, nullable=true, options={"comment":"Sous domaine"})
     */
    private $subdomain;

    /**
     * @var text
     *
     * @ORM\Column(name="header_top", type="text", nullable=true, options={"comment":"Haut de l'entête des pages"})
     */
    private $headerTop;

    /**
     * @var text
     *
     * @ORM\Column(name="footer", type="text", nullable=true, options={"comment":"Pied de page"})
     */
    private $footer;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=250, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=250, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="image_left", type="string", length=250, nullable=true)
     */
    private $imageLeft;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=250, nullable=true)
     */
    private $video;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="pageDetails", cascade={"persist"})
     * @ORM\JoinColumn(name="partner", referencedColumnName="id")
     */
    private $partner;


    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="integer", length=2, nullable=true, options={"default":0} )
     */
    private $deleted = 0;


    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param $deleted
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return mixed
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
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param $subdomain
     * @return $this
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param $video
     * @return $this
     */
    public function setVideo($video)
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageLeft()
    {
        return $this->imageLeft;
    }

    /**
     * @param string $imageLeft
     */
    public function setImageLeft($imageLeft)
    {
        $this->imageLeft = $imageLeft;
    }

    /**
     * @return text
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param text $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return text
     */
    public function getHeaderTop()
    {
        return $this->headerTop;
    }

    /**
     * @param text $headerTop
     */
    public function setHeaderTop($headerTop)
    {
        $this->headerTop = $headerTop;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }


}