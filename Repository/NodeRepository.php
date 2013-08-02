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
    public function getNodeForSlug($slug)
    {
        //take slug and find node that will match it

    }
}
