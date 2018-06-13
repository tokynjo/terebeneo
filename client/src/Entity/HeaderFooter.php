<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HeaderFooter
 *
 * @ORM\Table(name="header_footer")
 * @ORM\Entity(repositoryClass="App\Repository\HeaderFooterRepository")
 */
class HeaderFooter
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
     * @ORM\Column(name="header", type="text", nullable=true)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="footer", type="text", nullable=true)
     */
    private $footer;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="headersFooters", cascade={"persist"})
     * @ORM\JoinColumn(name="partner", referencedColumnName="id")
     */
    private $partner;

    /**
     * @var integer
     * @ORM\Column(name="isDeleted", type="integer", length=2, options={"default":0},nullable=false)
     */
    private $isDeleted;



    /**
     * HeaderFooter constructor.
     */

    public function __construct()
    {
        $this->isDeleted = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}
