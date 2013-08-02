<?php

namespace Btn\NodesBundle\Router;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Catch all with lowest priority and pass it to the NodeController
 */
class Router implements RouterInterface
{
    /** @var RouteCollection */
    private $routeCollection;

    /** @var UrlGenerator */
    private $urlGenerator;

    /** @var ContainerInterface */
    private $container;

    /** @var  RequestContext */
    private $context;

    /**
     * The constructor for this service
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container       = $container;
        $this->routeCollection = new RouteCollection();


        $this->routeCollection->add(
            '_btn_slug',
            new Route(
                '/{url}',
                array(
                    '_controller'   => 'BtnNodesBundle:Node:resolve',
                    'url'           => '',
                ),
                array('url' => "[a-zA-Z0-9\-_\/]*")
            )
        );
    }


    /**
     * Match given urls via the context to the routes we defined.
     * This functionality re-uses the default Symfony way of routing and its components
     *
     * @param string $pathinfo
     *
     * @return array
     */
    public function match($pathinfo)
    {
        $urlMatcher = new UrlMatcher($this->routeCollection, $this->getContext());

        $result = $urlMatcher->match($pathinfo);
        if (!empty($result)) {
            // The route matches, now check if it actually exists
            // $em = $this->container->get('doctrine.orm.entity_manager');
            // $nodeRepo = $em->getRepository('BtnNodesBundle:Node');
            // $node = $nodeRepo->getNodeTranslationForUrl($result['url']);

            // if (is_null($node)) {
            //     throw new ResourceNotFoundException('No page found for slug ' . $pathinfo);
            // }
        }

        return $result;
    }


    /**
     * Generate an url for a supplied route
     *
     * @param string $name       The path
     * @param array  $parameters The route parameters
     * @param bool   $absolute   Absolute url or not
     *
     * @return null|string
     */
    public function generate($name, $parameters = array(), $absolute = false)
    {
        $this->urlGenerator = new UrlGenerator($this->routeCollection, $this->context);

        return $this->urlGenerator->generate($name, $parameters, $absolute);
    }


    /**
     * Getter for routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }


    /**
     * Sets the request context.
     *
     * @param RequestContext $context The context
     *
     * @api
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }


    /**
     * Gets the request context.
     *
     * @return RequestContext The context
     *
     * @api
     */
    public function getContext()
    {
        if (!isset($this->context)) {
            /* @var Request $request */
            $request = $this->container->get('request');

            $this->context = new RequestContext();
            $this->context->fromRequest($request);
        }

        return $this->context;
    }
}
