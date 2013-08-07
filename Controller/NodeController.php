<?php

namespace Btn\NodesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Btn\BaseBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\NodesBundle\Entity\Node;

/**
 * Nodes resolver.
 *
 */
class NodeController extends BaseController
{
    /**
     * Resolve slug router
     */
    public function resolveAction($url = null)
    {
        //resolve node by url
        if ($node = $this->getRepository('BtnNodesBundle:Node')->getNodeForUrl($url)) {
            // $route = '/' . $node->getRoute();
            // $match = $this->get('router')->match($route);
            $uri = $this->get('router')->generate($node->getRoute(), $node->getRouteParameters());
            $uri = str_replace($this->get('request')->getBaseUrl(), '', $uri);
            $match = $this->get('router')->match($uri);

            if (isset($match['_controller'])) {

                //some additional controller attributes
                $context = array(
                    'url' => $url
                );

                //store as referrer
                $this->get('session')->set('_btn_slug', $url);
                $response = $this->forward($match['_controller'], array_merge($match, $context));

                //something here?

                return $response;
            }
        }

        //nothing matched
        throw $this->createNotFoundException('No page found for slug ' . $url);
    }
}