<?php
/**
 * Created by PhpStorm.
 * User: Rene
 * Date: 1/26/14
 * Time: 3:21 PM
 */
namespace Renelems\BackofficeBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Renelems\DBBundle\Entity\Admin;
use Renelems\BackofficeBundle\Manager\AdminManager;

class AddAdminUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:addUser')
            ->setDescription('Adds an admin User')
            ->addArgument('name', InputArgument::REQUIRED, 'Please give the name?')
            ->addArgument('username', InputArgument::REQUIRED, 'Please give the username?')
            ->addArgument('password', InputArgument::REQUIRED, 'Please give the password?')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sName = $input->getArgument('name');
        $sUsername = $input->getArgument('username');
        $sPassword = $input->getArgument('password');


        /** @var EntityManager $oEm  */
        $oEm = $this->getContainer()->get('doctrine')->getManager();


        $factory = $this->getContainer()->get('security.encoder_factory');
        $oAdmin = new Admin();
        $oAdmin->setName($sName);
        $oAdmin->setEmail($sUsername);
        $oAdmin->setType('admin');
        $oAdmin->newpassword = $sPassword;

        /** @var AdminManager $oAdminManager  */
        $oAdminManager = $this->getContainer()->get('renelems.managers.admin');
        $oAdminManager->createAdmin($oAdmin);
    }
}

