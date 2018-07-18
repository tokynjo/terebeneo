<?php
namespace App\Command;


use App\Entity\Constants\Constant;
use App\Entity\InstallSaveLog;
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
            )
            ->addOption(
                'simulation',
                's',
                InputOption::VALUE_NONE,
                'To send only notification for the user created via simulation page',
                NULL
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime("now");
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        if ($input->getOption('simulation')) {
            $partners = $this->em->getRepository("App:Partner")->findBy(['simulation' => 1, 'deleted' => 0]);

        } else {
            $partners = $this->em->getRepository("App:Partner")->findBy(['simulation' => 0, 'deleted' => 0]);
        }
        $service = $this->getContainer()->get(NeobeApiService::SERVICE_NAME);
        if ($partners) {
            foreach ($partners as $partner) {
                $logEtapOne = $this->em->getRepository("App:ValidationLog")
                    ->findBy(["etape" => Constant::STEP_ONE, "partner"=>$partner]);

                if($partner->getCreatedAt()) {
                    $dDiff = $now->diff($partner->getCreatedAt());
                    if (!$logEtapOne && ($dDiff->days >= 6)) { // 7Days
                        $notif = $this->em->getRepository("App:Notification")->find(Constant::NOTIF_NEOBE_VALIDATION_STEP_ONE);
                        $this->getContainer()
                            ->get("app.notification_service")->sendNotification($partner, $notif);
                    }
                }
                if($partner->getNeobeAccountId()) {
                    $auth = $service->getInfosAccount($partner->getNeobeAccountId());
                    if (isset($auth->data->compte)) {
                        foreach ($auth->data->compte as $compte) {
                            $compteNeobe = $this->em->getRepository("App:NeobeAccount")->findOneBy(["neobeAccountId" => $compte->id]);
                            if (!$compteNeobe) {
                                $compteNeobe = new NeobeAccount();
                                $compteNeobe->setPartner($partner);
                                $compteNeobe->setNeobeAccountId($compte->id);
                                $compteNeobe->setUpdatedAt($now);
                                $compteNeobe->setCreatedAt($now);
                            }
                            $compteNeobe->setPassword($compte->motdepasse);
                            $compteNeobe->setTotalSize($compte->espace);
                            $compteNeobe->setUsedSize($compte->espace_utilise);
                            $compteNeobe->setLogin($compte->login);
                            $compteNeobe->setInstalled(($compte->installed == true) ? 1 : 0);
                            $compteNeobe->setSaved(($compte->has_saved == true) ? 1 : 0);
                            $this->em->persist($compteNeobe);
                            $this->em->flush();
                            if($compte->installed == true) {
                                $newLogSave = new InstallSaveLog();
                                $newLogSave->setNeobeAccount($compteNeobe);
                                $newLogSave->setType(Constant::LOG_INSTALL);
                                $this->em->persist($newLogSave);
                                $this->em->flush();
                            }elseif($logEtapOne){
                                $dDiff = $now->diff($logEtapOne[0]->getCreatedAt());
                                if(($dDiff->days%1 == 0)) {
                                    $notif = $this->em->getRepository("App:Notification")->find(Constant::NOTIF_NEOBE_INSTALL);
                                    $this->getContainer()
                                        ->get("app.notification_service")->sendNotification($partner, $notif);
                                }
                            }
                            if ($compte->has_saved == true) {
                                $newLogSave = new InstallSaveLog();
                                $newLogSave->setNeobeAccount($compteNeobe);
                                $newLogSave->setType(Constant::LOG_SAVE);
                                $this->em->persist($newLogSave);
                                $this->em->flush();
                            }elseif($compte->installed == true){
                                $newLogSave = $this->em->getRepository("App:InstallSaveLog")
                                    ->findOneBy(["type" => Constant::LOG_INSTALL,"neobeAccount" => $compteNeobe]);
                                $dDiff = $now->diff($newLogSave->getCreatedAt());
                                if(($dDiff->days%1 == 0)) {
                                    $notif = $this->em->getRepository("App:Notification")->find(Constant::NOTIF_NEOBE_SAVE);
                                    $this->getContainer()
                                        ->get("app.notification_service")->sendNotification($partner, $notif);
                                }
                            }
                        }
                    }
                }
            }
        }
        die("Exit");
    }
}
