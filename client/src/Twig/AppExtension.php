<?php
namespace App\Twig;

use App\Entity\Partner;
use App\Manager\PageDetailsManager;
use App\Manager\PartnerManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;

/**
 * Class AppTwigExtension
 *
 * @package AppBundle\Twig
 */
class AppExtension  extends AbstractExtension
{
    const SERVICE_NAME = 'app.twig.app_extention';

    protected $container ;
    protected $request ;
    protected $partner = null;

    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->request = $requestStack->getCurrentRequest();
        $this->loadPartner();
    }

    public function getFilters()
    {
        return [
            //new \Twig_SimpleFilter('widgetLogo', array($this, 'widgetLogo'))
        ];
    }
    public function getFunctions()
    {
        return [
            new \Twig_Function('widgetLogo', array($this, 'widgetLogo')),
            new \Twig_Function('widgetVideoDemo', array($this, 'widgetVideoDemo')),
            new \Twig_Function('widgetHeaderTop', array($this, 'widgetHeaderTop')),
            new \Twig_Function('widgetFooter', array($this, 'widgetFooter')),
            new \Twig_Function('widgetColor', array($this, 'widgetColor')),
            new \Twig_Function('widgetImageLeft', array($this, 'widgetImageLeft')),
            new \Twig_Function('widgetResume1', array($this, 'widgetResume1'))
        ];
    }

    /**
     * generate image logo for the partner
     * @return string
     */
    public function widgetLogo()
    {
        $html = '';
        $path = DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'partner'.DIRECTORY_SEPARATOR.'img';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails) {
                $path .= DIRECTORY_SEPARATOR.$this->partner->getId().DIRECTORY_SEPARATOR
                    .$pageDetails->getLogo();
                $html .= '<img class="partner-logo"  src="'.$path.'" alt="'.$this->partner->getName().'" title="'
                    .$this->partner->getName().'">';
            }
        }

        return $html;
    }

    public function widgetVideoDemo()
    {
        //the default video
        $html = '<iframe src="https://player.vimeo.com/video/161032132" width="665" height="414"
                            frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getVideo()) {
                $html = $pageDetails->getVideo();
            }
        }

        return $html;
    }
    public function widgetHeaderTop()
    {
        //the default video
        $html = '<ul>
                    <li>Sauvegarde informatique NeoBe + Pour Safe Data</li>
                    <li><a href="tel:01.46.08.83.70">01.46.08.83.70</a></li>
                    <li><a href="mailto:commercial@neobe.com">commercial@neobe.com</a></li>
                </ul>';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getHeaderTop()) {
                $html = $pageDetails->getHeaderTop();
            }
        }

        return $html;
    }

    public function widgetFooter()
    {
        //the default video
        $html = '<div class="text-center">
                NeoBe + pour Safe Data - DropCloud 2018 - <small><a href="#">Mentions légales</a></small>
                </div>';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getFooter()) {
                $html = $pageDetails->getFooter();
            }
        }

        return $html;
    }
    public function widgetImageLeft()
    {
        //the default video
        $html = '<img src="/front/img/reseaulogos.jpg" alt="system" title="Système Neobe Safe Data" width="100%">';
        $path = DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'partner'.DIRECTORY_SEPARATOR.'img';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getImageLeft()) {
                $path .= DIRECTORY_SEPARATOR.$this->partner->getId().DIRECTORY_SEPARATOR
                    .$pageDetails->getImageLeft();
                $html = '<img class="neobe-system" width="100%"  src="'.$path.'" alt="Systeme Neobe" title="Systeme Neobe">';
            }
        }

        return $html;
    }

    public function widgetColor()
    {
        //the default video
        $html = '.step.active i {
                    background-color: #ed6b39;
                    border: 2px solid #ed6b39 !important;
                    color: #ffffff;
                }
                .step.active {
                    color :#ed6b39;
                }
                .link_1 {
                    background-color: #ed6b39;
                    border: 2px solid #ed6b39!important;
                }';

        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getColor()) {
                $html = '.step.active i {
                    background-color: '.$pageDetails->getColor().';
                    border: 2px solid '.$pageDetails->getColor().'!important;
                    color: #ffffff;
                    }
                    .step.active {
                        color :'.$pageDetails->getColor().';
                    }
                    .link_1 {
                    background-color: '.$pageDetails->getColor().';
                    border: 2px solid '.$pageDetails->getColor().'!important;
                }';
            }
        }

        return $html;
    }

    public function widgetResume1()
    {
        $html = '';
        if (!is_null($this->partner)) {
            $pageDetails = $this->partner->getActivePageDetails();
            if ($pageDetails && $pageDetails->getResume1()) {
                $html = $pageDetails->getResume1();
            }
        }
        return $html;
    }

    protected function loadPartner()
    {
        if (!is_null($this->request) ) {
            if ($this->request->get('token')) {
                $partner = $this->container
                    ->get(PartnerManager::SERVICE_NAME)->findOneBy(['hash' => $this->request->get('token')]);
                if ($partner) {
                    $this->partner = $partner->getParent();
                }
            }
            if (is_null($this->partner)) {
                $pageDetails = $this->container
                    ->get(PageDetailsManager::SERVICE_NAME)->findOneBy(['subdomain' => $this->request->getHost()]);
                if ($pageDetails) {
                    $this->partner = $pageDetails->getPartner();
                }

            }
        }
    }
}