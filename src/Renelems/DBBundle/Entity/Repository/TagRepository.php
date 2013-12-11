<?php
namespace Renelems\DBBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectImageRepository
 */
class TagRepository extends EntityRepository
{
    public function autocompleteSearch($sTerm)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select("t")
            ->from("RenelemsDBBundle:Tag", "t")
            ->where("t.title LIKE :term")
            ->setParameter('term', '%'.$sTerm.'%')
            ->getQuery()
            ->setMaxResults(100)
            ->getResult();
    }
}
