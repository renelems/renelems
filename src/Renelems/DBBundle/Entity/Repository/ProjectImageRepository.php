<?php
namespace Renelems\DBBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectImageRepository
 */
class ProjectImageRepository extends EntityRepository
{
    public function findInbetweenSequence($iOldPosition, $iNewPosition, $oImage)
    {
        $aReturn = $this->createQueryBuilder("l")
            ->where("l.project = :project_id")
            ->andWhere("l.sequence >= :start_sequence")
            ->andWhere("l.sequence <= :end_sequence")
            ->andWhere("l.id != :id")
            ->setParameters(array(
            		'start_sequence' => $iOldPosition,
            		'end_sequence' => $iNewPosition,
           			'project_id' => $oImage->getProject()->getId(),
            		'id' => $oImage->getId(),
    		))
            ->getQuery()
        	->getResult();
        return $aReturn;
    }

    public function findGreaterThanSequence($iSequence, $iProjectId)
    {
    	$aReturn = $this->createQueryBuilder("l")
    	->where("l.project = :project_id")
    	->andWhere("l.sequence > :sequence")
    	->setParameters(array(
    			'sequence' => $iSequence,
    			'project_id' => $iProjectId,
    	))
    	->getQuery()
    	->getResult();
    	return $aReturn;
    }
}
