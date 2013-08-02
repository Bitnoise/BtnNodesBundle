<?php

namespace Btn\NodesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Btn\BaseBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\NodesBundle\Entity\Node;
use Btn\NodesBundle\Form\NodeType;
/**
 * Nodes controller.
 *
 * @Route("/control/nodes")
 */
class NodeControlController extends BaseController
{
    /**
     * Lists all Nodes.
     *
     * @Route("/", name="cp_nodes")
     * @Template()
     */
    public function indexAction()
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('BtnNodesBundle:Node');
        $topNodes = $repo->getRootNodes();

        return array('topNodes' => $topNodes);
    }

    /**
     * Add new node
     *
     * @Route("/add", name="cp_add_node")
     */
    public function addAction(Request $request)
    {
        $parent = $this->findEntity('BtnNodesBundle:Node', $request->get('id'));
        $node = new Node();
        $node->setTitle('new item');
        if ($parent) {
            $node->setParent($parent);
        }

        $this->getManager()->persist($node);
        $this->getManager()->flush();

        $msg = $this->get('translator')->trans('node.created');
        $this->get('session')->getFlashBag()->add('success', $msg);

        return $this->redirect($this->generateUrl('cp_nodes'));
    }

    /**
     * Add new node
     *
     * @Route("/remove", name="cp_remove_node")
     */
    public function removeAction(Request $request)
    {
        $node = $this->findEntityOr404('BtnNodesBundle:Node', $request->get('id'));
        $this->getManager()->remove($node);
        $this->getManager()->flush();

        $msg = $this->get('translator')->trans('node.removed');
        $this->get('session')->getFlashBag()->add('success', $msg);

        return $this->redirect($this->generateUrl('cp_nodes'));
    }

    /**
     * Edit node params
     *
     * @Route("/edit/{id}", name="cp_edit_node")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $node   = $this->findEntityOr404('BtnNodesBundle:Node', $request->get('id'));
        $form   = $this->createForm(new NodeType(), $node);
        $result = null;

        //form processing
        $result = $this->processForm($node, $form, $request);

        //prepare content
        $content = $this->renderView('BtnNodesBundle:NodeControl:_form.html.twig', array(
            'form'       => $form->createView(),
            'id'         => $id
        ));

        //valid or without post
        if ($result === true || $result === null) {

            return $this->renderJson($content, 'success');
        //invalid
        } elseif ($result === false) {

            return $this->renderJson($content, 'error');
        }
    }

    /**
     * Select node content
     *
     * @Route("/content", name="cp_content_for_node")
     * @Template()
     */
    public function contentAction(Request $request)
    {
        //prepare content
        $content = $this->renderView('BtnNodesBundle:NodeControl:_content.html.twig', array(
        ));

        return $this->renderJson($content, 'succes');
    }

    private function processForm($entity, &$form, $request)
    {
        if ($request->getMethod() == 'POST' && $request->get($form->getName())) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getManager();
                $em->persist($entity);
                $em->flush();

                return true;
            } else {

                return false;
            }
        }
    }
}
