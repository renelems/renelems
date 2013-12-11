<?php
namespace Renelems\BackofficeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Doctrine\ORM\EntityManager;

use Renelems\DBBundle\Entity\ProjectImage;
use Renelems\DBBundle\Entity\Admin;
use Symfony\Component\HttpFoundation\Response;
use Renelems\DBBundle\Entity\Tag;

/**
 * Admin controller.
 *
 * @Route("/api")
 */
class ApiController extends Controller
{
	/**
	 *
	 * @Route("/update-image-position", name="admin_api_image_position")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateImagePositionAction()
	{
		$oRequest = $this->getRequest();
		$oEm = $this->getDoctrine()->getManager();
		
		$sObject = $oRequest->get("object");
		$iId = $oRequest->get("id");
		$iOldPosition = intval($oRequest->get("oldPosition"));
		$iNewPosition = intval($oRequest->get("newPosition"));
		
		$oTargetImage = $oEm->getRepository("RenelemsDBBundle:".$sObject)->find($iId);
		
		if($iNewPosition < $iOldPosition) {
			$aImages = $oEm->getRepository("RenelemsDBBundle:".$sObject)->findInbetweenSequence($iNewPosition, $iOldPosition, $oTargetImage);
			
			foreach($aImages as $oImage) {
				$oImage->setSequence($oImage->getSequence()+1);
				$oEm->persist($oImage);
			}
		} else {
			$aImages = $oEm->getRepository("RenelemsDBBundle:".$sObject)->findInbetweenSequence($iOldPosition, $iNewPosition, $oTargetImage);
			
			foreach($aImages as $oImage) {
				$oImage->setSequence($oImage->getSequence()-1);
				$oEm->persist($oImage);
			}
		}
		
		$oTargetImage->setSequence($iNewPosition);
		$oEm->persist($oTargetImage);
		$oEm->flush();
		
		return new Response(json_encode(array('result' => 'ok')));
	}
	
	/**
	 *
	 * @Route("/update-image-remove", name="admin_api_image_remove")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Method("POST")
	 * @Template()
	 */
	public function removeImageAction()
	{
		$oRequest = $this->getRequest();
		$oEm = $this->getDoctrine()->getManager();
		
		$sObject = $oRequest->get("object");
		$iId = $oRequest->get("id");
		
		$oTargetImage = $oEm->getRepository("RenelemsDBBundle:".$sObject)->find($iId);
		$iSequence = $oTargetImage->getSequence();
		$iProjectId = $oTargetImage->getProject()->getId();
		 
		if($oTargetImage !== NULL) {
			unlink($this->container->getParameter('project_image_dir').$oTargetImage->getPath());
			unlink($this->container->getParameter('project_image_dir')."thumb_".$this->container->getParameter('thumbnail_width')."/".$oTargetImage->getPath());
			$oEm->remove($oTargetImage);
		}
		
		$aImages = $oEm->getRepository("RenelemsDBBundle:".$sObject)->findGreaterThanSequence($iSequence, $iProjectId);
		
		foreach ($aImages as $oImage) {
			$oImage->setSequence($oImage->getSequence()-1);
			$oEm->persist($oImage);
		}
		$oEm->flush();
		
		return new Response(json_encode(array('result' => 'ok')));
	}
	
	/**
	 *
	 * @Route("/toggle-active", name="admin_api_toggle_active")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Method("POST")
	 * @Template()
	 */
	public function toggleActiveAction()
	{
		$oRequest = $this->getRequest();
		$oEm = $this->getDoctrine()->getManager();
		
		$sObject = $oRequest->get("object");
		$iId = $oRequest->get("id");
		
		$oEntity = $oEm->getRepository("RenelemsDBBundle:".$sObject)->find($iId);
		
		if($oEntity->getActive())
			$oEntity->setActive(false);
		else 
			$oEntity->setActive(true);
		
		$oEm->persist($oEntity);
		$oEm->flush();
		
		return new Response(json_encode(array('result' => 'ok', 'active' => $oEntity->getActive())));
	}
	
	/**
	 *
	 * @Route("/autocomplete", name="admin_api_autocomplete")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Template()
	 */
	public function autocompleteAction()
	{
		$oRequest = $this->getRequest();
        $sObjectType = $oRequest->get('object', null);
        $sSearch = $oRequest->get('search', null);

        if($sObjectType === null || $sSearch === null) {
            return new Response("Incorrect request, missing parameters", 500);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $oEntityRepository = null;

        switch($sObjectType) {
            case "tag":
                $oEntityRepository = $em->getRepository('RenelemsDBBundle:Tag');
                break;
            default:
                break;
        }
        
        $aResponse = array();
        
        if($oEntityRepository === null) {
            return new Response("Incorrect parameters", 500);
        }

        $aEntities = $oEntityRepository->autocompleteSearch($sSearch);
        
        foreach($aEntities as $oEntity) {
        	$aResponseItem = array();
        	$aResponseItem["value"] = $oEntity->getId();
        	$aResponseItem["label"] = (string) $oEntity->getTitle();
        	$aResponse[] = $aResponseItem;
        }
        	
        $oResponse = new Response(json_encode($aResponse));
        return $oResponse;
	}
	
	/**
	 *
	 * @Route("/add-tag", name="admin_api_add_tag")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Template()
	 */
	public function addTagAction()
	{
		$oRequest = $this->getRequest();
		$oEm = $this->getDoctrine()->getManager();
		
		$sTitle = ucfirst($oRequest->get("title"));
		
		$oEntity = $oEm->getRepository("RenelemsDBBundle:Tag")->findOneByTitle($sTitle);
		
		if($oEntity === null) {
			$oEntity = new Tag();
			$oEntity->setTitle($sTitle);
			$oEm->persist($oEntity);
			$oEm->flush();
		}
		
		$aResponse['id'] = $oEntity->getId();
		$aResponse['title'] = $oEntity->getTitle();
		
		$oResponse = new Response(json_encode($aResponse));
		return $oResponse;
	}
}