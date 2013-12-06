<?php

namespace Btn\NodesBundle\Provider;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\Loader\LoaderInterface;

class NodeMenuProvider implements MenuProviderInterface
{
    /**
     * @var FactoryInterface
     */
    protected $factory = null;
    protected $loader = null;
    protected $em = null;
    protected $nodeArrayCache = array();

    /**
     * @param FactoryInterface $factory the menu factory used to create the menu item
     */
    public function __construct(FactoryInterface $factory, LoaderInterface $loader, $em)
    {
        $this->factory = $factory;
        $this->loader  = $loader;
        $this->em      = $em;
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        $menu = $this->getNodeForSlugWithCache($name);

        if ($menu === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        $menuItem = $this->loader->load($menu);

        //add class if provided
        if (isset($options['class'])) {
            $menuItem->setChildrenAttribute('class', $options['class']);
        }

        return $menuItem;
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     * @return bool
     */
    public function has($name, array $options = array())
    {
        $menu = $this->getNodeForSlugWithCache($name);

        return $menu !== null;
    }

    /**
     * Get node for slug with local array cache to prevent unnecessary
     */
    protected function getNodeForSlugWithCache($name)
    {
        if (!array_key_exists($name, $this->nodeArrayCache)) {
            $this->nodeArrayCache[$name] = $this->em->getRepository('BtnNodesBundle:Node')->getNodeForSlug($name);
        }

        return $this->nodeArrayCache[$name];
    }
}
