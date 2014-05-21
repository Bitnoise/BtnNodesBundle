<?php

namespace Btn\NodesBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\Menu\NodeInterface;
use Btn\BaseBundle\Util\Text;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="nodes", indexes={@ORM\Index(name="idx_slug", columns={"slug"}), @ORM\Index(name="idx_url", columns={"url"}), @ORM\Index(name="idx_root", columns={"root"})})
 * use repository for handy tree functions
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Btn\NodesBundle\Repository\NodeRepository")
 */
class Node implements NodeInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string", length=64, nullable=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\Column(name="route", type="string", nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(name="route_parameters", type="string", nullable=true)
     */
    private $routeParameters;

    /**
     * @ORM\Column(name="control_route_parameters", type="string", nullable=true)
     */
    private $controlRouteParameters;

    /**
     * @ORM\Column(name="control_route", type="string", nullable=true)
     */
    private $controlRoute;

    /**
     * @ORM\Column(name="provider", type="string", nullable=true)
     */
    private $provider;

    /**
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    private $metaTitle;

    /**
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * @ORM\Column(name="og_title", type="string", nullable=true)
     */
    private $ogTitle;

    /**
     * @ORM\Column(name="og_description", type="text", nullable=true)
     */
    private $ogDescription;

    /**
     * @ORM\ManyToOne(targetEntity="Btn\MediaBundle\Entity\MediaFile")
     * @ORM\JoinColumn(name="og_image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ogImage;

    /**
     * @ORM\Column(name="visible", type="boolean", options={"default" = 1})
     */
    private $visible = true;

    /**
     * @ORM\Column(name="link", type="string", nullable=true)
     */
    private $link;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $router = null;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        return $this->slug = Text::slugify($slug);
    }

    public function setParent(Node $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     *
     */
    public function clearChildren()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

        return $this;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Node
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Node
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Node
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Node
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Node
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeParameters
     *
     * @param string $routeParameters
     * @return Node
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = serialize($routeParameters);

        return $this;
    }

    /**
     * Get routeParameters
     *
     * @return string
     */
    public function getRouteParameters()
    {
        return unserialize($this->routeParameters);
    }

    /**
     * Add children
     *
     * @param \Btn\NodesBundle\Entity\Node $children
     * @return Node
     */
    public function addChildren(\Btn\NodesBundle\Entity\Node $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Btn\NodesBundle\Entity\Node $children
     */
    public function removeChildren(\Btn\NodesBundle\Entity\Node $children)
    {
        $this->children->removeElement($children);
    }


    /**
     * @return string
     */
    public function getFullSlug($withoutThisNode = false)
    {
        $slug       = "";
        $parentNode = $this->getParent();
        if ($parentNode != null) {
            $parentSlug = $parentNode->getFullSlug();
            if (!empty($parentSlug)) {
                $slug = rtrim($parentSlug, "/") . "/";
            }
        }

        if (!$withoutThisNode) {
            $slug = $this->getLvl() !== 0 ? $slug . $this->getSlug() : '';
        }

        return $slug;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Node
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * Update full url for this node
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
     public function updateUrl()
     {
        //don't update url for root nodes
        $parentNode = $this->getParent();
        if ($parentNode != null) {
            $this->url = $this->getFullSlug();
        }
     }

    /**
     * Set controlRoute
     *
     * @param string $controlRoute
     * @return Node
     */
    public function setControlRoute($controlRoute)
    {
        $this->controlRoute = $controlRoute;

        return $this;
    }

    /**
     * Get controlRoute
     *
     * @return string
     */
    public function getControlRoute()
    {
        return $this->controlRoute;
    }

    /**
     * Set provider
     *
     * @param string $provider
     * @return Node
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    public function getName()
    {
        return $this->title;
    }

    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getOptions()
    {
        if (!$this->getRoute()) {
            return array();
        } elseif ($this->getUrl()) {
            return array(
                'uri' => $this->router ? $this->router->generate('_btn_slug', array('url' => $this->getUrl())) : $this->getUrl(),
            );
        } else {
            return array(
                'route'           => $this->getRoute(),
                'routeParameters' => is_array($this->getRouteParameters()) ? $this->getRouteParameters() : array()
            );
        }
    }

    /**
     * Set routeParameters
     *
     * @param string $routeParameters
     * @return Node
     */
    public function setControlRouteParameters($routeParameters)
    {
        $this->routeParameters = serialize($routeParameters);

        return $this;
    }

    /**
     * Get routeParameters
     *
     * @return string
     */
    public function getControlRouteParameters()
    {
        return unserialize($this->routeParameters);
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Node
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Node
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Node
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set ogTitle
     *
     * @param string $ogTitle
     * @return Node
     */
    public function setOgTitle($ogTitle)
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    /**
     * Get ogTitle
     *
     * @return string
     */
    public function getOgTitle()
    {
        return $this->ogTitle;
    }

    /**
     * Set ogDescription
     *
     * @param string $ogDescription
     * @return Node
     */
    public function setOgDescription($ogDescription)
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    /**
     * Get ogDescription
     *
     * @return string
     */
    public function getOgDescription()
    {
        return $this->ogDescription;
    }

    /**
     * Set ogImage
     *
     * @param \Btn\MediaBundle\Entity\MediaFile $image
     * @return Node
     */
    public function setOgImage(\Btn\MediaBundle\Entity\MediaFile $ogImage = null)
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    /**
     * Get ogImage
     *
     * @return \Btn\MediaBundle\Entity\MediaFile
     */
    public function getOgImage()
    {
        return $this->ogImage;
    }

    public function __toString()
    {
        return str_pad($this->title, strlen($this->title) + $this->lvl, "_", STR_PAD_LEFT);
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Node
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Node
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
