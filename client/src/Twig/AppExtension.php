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
            new \Twig_Function('widgetVideoDemo', array($this, 'widgetVideoDemo'))
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
        $pageDetails = $this->partner->getActivePageDetails();
        if ($pageDetails) {
            $path .= DIRECTORY_SEPARATOR.$this->partner->getId().DIRECTORY_SEPARATOR
                .$pageDetails->getLogo();
            $html .= '<img class="partner-logo"  src="'.$path.'" alt="'.$this->partner->getName().'" title="'
                .$this->partner->getName().'">';
        }

        return $html;
    }

    public function widgetVideoDemo()
    {
        $html = '';
        $pageDetails = $this->partner->getActivePageDetails();
        if ($pageDetails) {
            $html .= $pageDetails->getVideo();
        }

        return $html;
    }

    protected function loadPartner()
    {
        if ($this->request->get('token')) {
            $partner = $this->container
                ->get(PartnerManager::SERVICE_NAME)->findOneBy(['hash'=>$this->request->get('token')]);
            if($partner) {
                $this->partner = $partner->getParent();
            }
        }
        if (is_null($this->partner)) {
            $pageDetails = $this->container
                ->get(PageDetailsManager::SERVICE_NAME)->findOneBy(['subdomain'=>$this->request->getHost()]);
            if($pageDetails) {
                $this->partner = $pageDetails->getPartner();
            }

        }
    }
}