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
}