<?php
namespace App\Command;


use App\Entity\Constants\Constant;
use App\Manager\FileManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteFileCommand extends ContainerAwareCommand
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
            ->setName('wedrop:file:delete')
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
        $files = $this->getContainer()->get(FileManager::SERVICE_NAME)->findBy(
            [
                "status"=>Constant::FILE_STATUS_DELETED
            ]
        );
        foreach ($files as $file){
                $user = $file->getUser();
                $file->setStatus(Constant::FILE_STATUS_DELETE_OPENSTACK);
                $this->getContainer()->get(FileManager::SERVICE_NAME)->saveAndFlush($file);
                $this->getContainer()->get('app.openstack.objectstore')->deleteFile($file, $user);
        }
    }
}
