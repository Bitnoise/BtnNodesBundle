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
    public function resolveAction($url = null, Node $node = null)
    {

        //301 redirect if url has backslash at the end
        if ('/' != $url AND '/' == substr($url, -1)) {
            $testDir = realpath(__DIR__ . '/../../../../../../web/' . $url);
            if (!is_dir($testDir) AND !file_exists($testDir)) {
                $request = $this->getRequest();
                $qs = $request->getQueryString();
                if ($qs) {
                    $qs = '?' . $qs;
                }
                $redirectUrl = $request->getBaseUrl() . '/' . trim($url, '/') . $qs;
                return $this->redirect($redirectUrl, 301);
            }
        }

        //fix url if has trailing slash
        $url = rtrim($url, '/');

        //resolve node by url
        if ($node || ($node = $this->getRepository('BtnNodesBundle:Node')->getNodeForUrl($url))) {
            //if node contains valid url - redirect
            $link = $node->getLink();
            if (!empty($link)) {

                return $this->redirect($link);
            }


            // $route = '/' . $node->getRoute();
            // $match = $this->get('router')->match($route);
            $uri = $this->get('router')->generate($node->getRoute(), $node->getRouteParameters());
            $uri = str_replace($this->get('request')->getBaseUrl(), '', $uri);
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
                $response = $this->forward($match['_controller'], array_merge($match, $context));

                //something here?

                return $response;
            }
        }

        //nothing matched
        throw $this->createNotFoundException('No page found for slug ' . $url);
    }
}
