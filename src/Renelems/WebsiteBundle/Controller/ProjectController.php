<?php
namespace Renelems\WebsiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Renelems\DBBundle\Entity\Project;

/**
 * Class ProjectController
 * @Route("/project")
 */
class ProjectController extends Controller
{
	/**
     * @Route("/", name="renelems_project")
     * @Template()
     */
    public function indexAction()
    {
    	$oEm = $this->getDoctrine()->getManager();
    	$aProject = $oEm->getRepository('RenelemsDBBundle:Project')->findAll();
    	
        return array(
        	'aProject'		=> $aProject,
        	'menu'         		=> 'project',
        	'seo_title'			=> 'Maakt websites op maat',
        	'seo_description'	=> '',
        );
    }
    
    /**
     * @Route("/{slug}", name="renelems_project_detail")
     * @Template()
     */
    public function detailAction($slug)
    {
    	$oEm = $this->getDoctrine()->getManager();
    	$oProject = $oEm->getRepository('RenelemsDBBundle:Project')->findOneBySlug($slug);

        if(!$oProject) {
            new $this->createNotFoundException("Unable to find project");
        }

    	return array(
    			'oProject'		    => $oProject,
    			'menu'         		=> 'project',
    			'seo_title'			=> 'Vrijblijvende informatie over website',
    			'seo_description'	=> '',
    	);
    }
}
