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
    public function resolveAction($url = null, $dupa = '')
    {
        //resolve node by url
        if ($node = $this->getRepository('BtnNodesBundle:Node')->getNodeForSlug($url)) {
            $route = '/' . $node->getRoute();
            $match = $this->get('router')->match($route);

            if (isset($match['_controller'])) {

                //some additional controller attributes
                $context = array(
                    'slug' => $url
                );

                //store as referrer
                $this->get('session')->set('_btn_slug', $url);

                $response = $this->forward($match['_controller'], $context);

                //something here?

                return $response;
            }
        }

        //nothing matched
        throw $this->createNotFoundException('No page found for slug ' . $url);
    }
}