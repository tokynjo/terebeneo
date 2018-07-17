<?php
namespace App\Entity;

use App\Entity\Constants\Constant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="resume_1", type="text", nullable=true, options={"comment":"Résumé sur la page première étape"})
     */
    private $resume1;

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
    private $color = '';

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
     * @var text
     *
     * @ORM\Column(name="product", type="string", length=250, nullable=true, options={"comment":"Label product"})
     */
    private $product;

    /**
     * @var text
     *
     * @ORM\Column(name="product_plus", type="string", length=250, nullable=true, options={"comment":"Label product plus"})
     */
    private $productPlus;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=250, nullable=true, options={"comment":"email contanct"})
     */
    private $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="string", length=250, nullable=true, options={"comment":"phone contact"})
     */
    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_title", type="string", length=250, nullable=true, options={"comment":"phone contact"})
     */
    private $contactTitle;

    /**
     * @var text
     *
     * @ORM\Column(name="legal_mention", type="text", nullable=true, options={"comment":"Mention légale"})
     */
    private $legalMention;

    /**
     * @var text
     * @ORM\Column(name="cgv", type="string", length=250, nullable=true, options={"comment":"term and condition"})
     */
    private $cgv;


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

    /**
     * @return text
     */
    public function getResume1()
    {
        return $this->resume1;
    }

    /**
     * @param text $resume1
     */
    public function setResume1($resume1)
    {
        $this->resume1 = $resume1;
    }

    /**
     * @return text
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param text $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return text
     */
    public function getProductPlus()
    {
        return $this->productPlus;
    }

    /**
     * @param $productPlus
     * @return $this
     */
    public function setProductPlus($productPlus)
    {
        $this->productPlus = $productPlus;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param $contactEmail
     * @return $this
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param $contactPhone
     * @return $this
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactTitle()
    {
        return $this->contactTitle;
    }

    /**
     * @param $contactTitle
     * @return $this
     */
    public function setContactTitle($contactTitle)
    {
        $this->contactTitle = $contactTitle;
        return $this;
    }

    /**
     * @return text
     */
    public function getLegalMention()
    {
        return $this->legalMention;
    }

    /**
     * @param $legalMention
     * @return $this
     */
    public function setLegalMention($legalMention)
    {
        $this->legalMention = $legalMention;
        return $this;
    }

    /**
     * @return text
     */
    public function getCgv()
    {
        return $this->cgv;
    }

    /**
     * @param $cgv
     * @return $this
     */
    public function setCgv($cgv)
    {
        $this->cgv = $cgv;
        return $this;
    }




}