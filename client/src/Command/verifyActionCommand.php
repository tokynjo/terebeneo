<?php
namespace App\Command;


use App\Entity\Constants\Constant;
use App\Entity\NeobeAccount;
use App\Services\NeobeApiService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class verifyActionCommand extends ContainerAwareCommand
{
    private $em = null;

    /**
     * Class DeleteFileCommand
     * init set constants data values in the databases
     *
     * @package App\Command
     */
    protected function configure()
    {
        $this
            ->setName('neobe_ter:activation')
            ->setDescription('Delete file into openstack')
            ->setHelp(
                <<<'EOT'
            The <info>wedrop:file:delete</info> to delete file in openstack
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $allPartners = $this->em->getRepository("App:Partner")->findAll();
        $service = $this->getContainer()->get(NeobeApiService::SERVICE_NAME);
        if ($allPartners) {
            foreach ($allPartners as $partner) {
                if($partner->getNeobeAccountId()) {
                    $auth = $service->getInfosAccount($partner->getNeobeAccountId());
                    if (isset($auth->data->compte)) {
                        $now = new \DateTime("now");
                        foreach ($auth->data->compte as $compte) {
                            $compteNeobe = $this->em->getRepository("App:NeobeAccount")->findOneBy(["idAccount" => $compte->id]);
                            if (!$compteNeobe) {
                                $compteNeobe = new NeobeAccount();
                                $compteNeobe->setPartner($partner);
                                $compteNeobe->setIdAccount($compte->id);
                                $compteNeobe->setUpdatedAt($now);
                                $compteNeobe->setCreatedAt($now);
                            }
                            $compteNeobe->setInstalled(($compte->installed == true) ? 1 : 0);
                            if ($compte->has_saved == true) {
                                $compteNeobe->setSaved(($compte->has_saved == true) ? 1 : 0);
                            }
                            $compteNeobe->setPassword($compte->motdepasse);
                            $compteNeobe->setTotalSize($compte->espace);
                            $compteNeobe->setUsedSize($compte->espace_utilise);
                            $compteNeobe->setLogin($compte->login);
                            $this->em->persist($compteNeobe);
                            $this->em->flush();
                        }
                    }
                }
            }
        }
        die("Exit");
    }
}
