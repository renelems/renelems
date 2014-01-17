<?php
namespace Renelems\WebsiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Renelems\DBBundle\Entity\Page;

class SystemController extends Controller
{
	/**
     * @Route("/", name="renelems_home")
     * @Template()
     */
    public function homeAction()
    {
    	$oEm = $this->getDoctrine()->getManager();
    	$oServicePage = $oEm->getRepository('RenelemsDBBundle:Page')->findOneBySlug('diensten');
    	
        return array(
        	'oServicePage'		=> $oServicePage,
        	'menu'         		=> 'home',
        	'seo_title'			=> 'Maakt websites op maat',
        	'seo_description'	=> '',
        );
    }
    
}
