<?php

namespace Btn\NodesBundle\Twig;

use Btn\NodesBundle\Provider\NodeMenuProvider;
use Doctrine\ORM\EntityManager;

class NodeMenuExtension extends \Twig_Extension
{
    /**
     * @var \Btn\NodesBundle\Provider\NodeMenuProvider
     */
    protected $nodeMenuProvider;
    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;
    protected $repo;

    public function __construct(NodeMenuProvider $nodeMenuProvider, EntityManager $em)
    {
        $this->nodeMenuProvider = $nodeMenuProvider;
        $this->em               = $em;
        $this->repo             = $this->em->getRepository('BtnNodesBundle:Node');
    }

    public function getFunctions()
    {
        return array(
            'btn_menu_has' => new \Twig_Function_Method($this, 'has'),
            'btn_get_node' => new \Twig_Function_Method($this, 'getNodeForUrl'),
        );
    }

    public function has($name, array $options = array())
    {
        return $this->nodeMenuProvider->has($name, $options);
    }

    public function getNodeForUrl($url)
    {
        return $this->repo->getNodeForUrl($url);
    }

    public function getName()
    {
        return 'btn_node.menu.extension';
    }
}
