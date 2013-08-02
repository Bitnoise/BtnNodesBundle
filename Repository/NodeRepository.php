<?php

namespace Btn\NodesBundle\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Kunstmaan\NodeBundle\Entity\Node;

/**
 * NodeRepository
 */
class NodeRepository extends NestedTreeRepository
{
    /**
     * @param string $slug       The slug
     *
     * @return Node|null
     */
    public function getNodeForSlug($url)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
        ;

        if (empty($url)) {
            $qb->andWhere('n.url IS NULL');
        } else {
            $qb->andWhere('n.url = :url');
            $qb->setParameter('url', $url);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
