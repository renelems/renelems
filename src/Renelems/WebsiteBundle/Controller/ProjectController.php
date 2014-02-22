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
     * @Route("/contact", name="renelems_contact")
     * @Template()
     */
    public function contactAction(Request $oRequest)
    {
    	$oEm = $this->getDoctrine()->getManager();
    	$oContactPage = $oEm->getRepository('RenelemsDBBundle:Page')->findOneBySlug('contact');
    	
    	$oForm = $this->createForm(new ContactType());
    	
    	
    	
    	if ($oRequest->isMethod('POST')) {
    		$oForm->bind($oRequest);
	    	if($oForm->isValid()) {
	    		$this->get('session')->getFlashBag()->add('notice','Uw e-mail is verstuurd! Ik zal binnen enkele dagen contact met u opnemen.');
	    		return $this->redirect($this->generateUrl('renelems_contact'));
	    	}
    	}
    	
    	return array(
    			'oContactPage'		=> $oContactPage,
    			'oForm' 			=> $oForm->createView(),
    			'menu'         		=> 'contact',
    			'seo_title'			=> 'Vrijblijvende informatie over website',
    			'seo_description'	=> '',
    	);
    }
}
