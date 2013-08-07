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
    public function getNodeForUrl($url)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->setFirstResult(0)
            ->setMaxResults(1);
        ;

        if (empty($url)) {
            $qb->andWhere('n.url IS NULL');
        } else {
            $qb->andWhere('n.url = :url');
            $qb->setParameter('url', $url);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $slug       The slug
     *
     * @return Node|null
     */
    public function getNodeForSlug($slug)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->setFirstResult(0)
            ->setMaxResults(1);
        ;

        $qb->andWhere('n.slug = :slug');
        $qb->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
