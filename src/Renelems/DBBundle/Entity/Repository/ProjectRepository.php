<?php

namespace Renelems\DBBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectRepository
 */
class ProjectRepository extends EntityRepository
{

    public function getOverviewImages()
    {
        $aReturn = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("pi")
            ->from("RenelemsDBBundle:ProjectImage", "pi")
            ->where("pi.type = :type")
            ->setParameter('type', 'overview')
            ->getQuery()
            ->getResult();
        return $aReturn;
    }

}
