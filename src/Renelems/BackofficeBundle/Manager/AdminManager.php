<?php
namespace Renelems\BackofficeBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;


use Renelems\DBBundle\Entity\Admin;

class AdminManager extends ContainerAware
{

    /**
     * @var EntityManager $oEm
     */
    public $oEm;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory $oFactory
     */
    public $oFactory;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $oRegistry
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactory $oFactory
     */

    public function __construct(Registry $oRegistry, \Symfony\Component\Security\Core\Encoder\EncoderFactory $oFactory) {
        $this->oEm = $oRegistry->getManager();
        $this->oFactory = $oFactory;
    }

    public function createAdmin(Admin $oAdmin) {
        $this->oEm->persist($oAdmin);

        $oAdmin->setSalt(sha1(time().$oAdmin->getEmail()));

        $sPassword = $oAdmin->newpassword;
        if($sPassword === null || strlen($sPassword) == 0) {
            $sPassword = substr(md5($oAdmin->getSalt()),rand(4,8),8);
        }

        $encoder = $this->oFactory->getEncoder($oAdmin);
        $password = $encoder->encodePassword($sPassword, $oAdmin->getSalt());
        $oAdmin->setPassword($password);

        $this->oEm->flush();
        return $oAdmin;
    }

    public function updateAdmin(Admin $oAdmin) {

        $sPassword = $oAdmin->newpassword;
        if($sPassword !== null || strlen($sPassword) !== 0) {
            $encoder = $this->oFactory->getEncoder($oAdmin);
            $password = $encoder->encodePassword($sPassword, $oAdmin->getSalt());
            $oAdmin->setPassword($password);
        }

        $this->oEm->flush();
        return $oAdmin;
    }
}
