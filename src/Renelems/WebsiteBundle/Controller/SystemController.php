<?php
namespace Renelems\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SystemController extends Controller
{
	/**
     * @Route("/", name="renelems_home")
     * @Template()
     */
    public function homeAction()
    {
        return array(
        	'menu'         		=> 'home',
        	'seo_title'			=> 'Maakt websites op maat',
        	'seo_description'	=> '',
        );
    }
    
}
