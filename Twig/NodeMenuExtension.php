<?php

namespace Btn\NodesBundle\Twig;

use Btn\NodesBundle\Provider\NodeMenuProvider;

class NodeMenuExtension extends \Twig_Extension
{
    /**
     * @var \Btn\NodesBundle\Provider\NodeMenuProvider
     */
    protected $nodeMenuProvider;

    public function __construct(NodeMenuProvider $nodeMenuProvider)
    {
        $this->nodeMenuProvider = $nodeMenuProvider;
    }

    public function getFunctions()
    {
        return array(
            'btn_menu_has' => new \Twig_Function_Method($this, 'has'),
            'btn_get_node' => new \Twig_Function_Method($this, 'getNodeByUri'),
        );
    }

    public function has($name, array $options = array())
    {
        return $this->nodeMenuProvider->has($name, $options);
    }

    public function getNodeByUri($uri)
    {
        return $this->nodeMenuProvider->getNodesByUri($uri);
    }

    public function getName()
    {
        return 'btn_node.menu.extension';
    }
}
