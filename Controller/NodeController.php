<?php

namespace Btn\NodesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Btn\BaseBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\NodesBundle\Entity\Node;
use Btn\BreadcrumbBundle\Model\Breadcrumb;

/**
 * Nodes resolver.
 *
 */
class NodeController extends BaseController
{
    /**
     * Resolve slug router
     */
    public function resolveAction($url = null, Node $node = null)
    {
        //resolve node by url
        if ($node || ($node = $this->getRepository('BtnNodesBundle:Node')->getNodeForUrl($url))) {
            //if node contains valid url - redirect
            $link = $node->getLink();
            if (!empty($link)) {

                return $this->redirect($link);
            }

            // $route = '/' . $node->getRoute();
            // $match = $this->get('router')->match($route);
            $request = $this->get('request');
            $uri = $this->get('router')->generate($node->getRoute(), $node->getRouteParameters());
            $uri = str_replace($request->getBaseUrl(), '', $uri);
            $match = $this->get('router')->match($uri);

            //prevent recursive loop here
            if (isset($match['_controller']) && $match['_controller'] !== 'Btn\NodesBundle\Controller\NodeController::resolveAction') {

                //some additional controller attributes
                $context = array(
                    'url'  => $url,
                    'node' => $node
                );

                //store as referrer
                $this->get('session')->set('_btn_slug', $url);

                //breadcrumb
                if ($node) {
                    $bcManager = $this->get('btn.bc');
                    $path = $this->getRepository('BtnNodesBundle:Node')->getPath($node);
                    foreach ($path as $pathNode) {
                        if ($pathNode->getRoute()) {
                            $bcManager->createItem($pathNode->getTitle(), $request->getBaseUrl() . '/' . $pathNode->getUrl());
                        }
                    }

                }

                $response = $this->forward($match['_controller'], array_merge($match, $context));

                //something here?

                return $response;
            }
        }

        //nothing matched
        throw $this->createNotFoundException('No page found for slug ' . $url);
    }
}
