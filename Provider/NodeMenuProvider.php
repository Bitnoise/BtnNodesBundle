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
        $menu = $this->em->getRepository('BtnNodesBundle:Node')->getNodeForSlug($name);

        if ($menu === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        /*
         * Populate your menu here
         */
        // $menuItem = $this->factory->createFromNode($menu);
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
        $menu = $this->em->getRepository('BtnNodesBundle:Node')->getNodeForSlug($name);

        return $menu !== null;
    }
}
