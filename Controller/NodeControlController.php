<?php

namespace Btn\NodesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\NodesBundle\Entity\Node;

/**
 * Nodes controller.
 *
 * @Route("/control/nodes")
 */
class NodeControlController extends Controller
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
     * @Route("/", name="cp_add_node")
     */
    public function addAction()
    {
        //TODO
    }

    /**
     * Add new node
     *
     * @Route("/", name="cp_remove_node")
     */
    public function removeAction()
    {
        //TODO
    }

    /**
     * @Route("/test", name="cp_nodes_test")
     */
    public function testAction()
    {
        $this->em = $this->getDoctrine()->getManager();

        $food = new Node();
        $food->setTitle('Main menu');

        $fruits = new Node();
        $fruits->setTitle('Fruits');
        $fruits->setParent($food);

        $vegetables = new Node();
        $vegetables->setTitle('Vegetables');
        $vegetables->setParent($food);

        $carrots = new Node();
        $carrots->setTitle('Carrots');
        $carrots->setParent($vegetables);

        $this->em->persist($food);
        $this->em->persist($fruits);
        $this->em->persist($vegetables);
        $this->em->persist($carrots);
        $this->em->flush();

        die();
    }
}
