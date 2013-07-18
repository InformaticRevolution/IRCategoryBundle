<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract category implementation.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class Category implements CategoryInterface
{
    /**
     * @var mixed
     */
    protected $id; 

    /**
     * @var CategoryInterface
     */
    protected $parent;    
    
    /**
     * @var string
     */
    protected $name; 
    
    /**
     * @var string
     */
    protected $slug;    

    /**
     * @var integer
     */
    protected $position;      

    /**
     * @var Collection
     */
    protected $children;      

    
    /**
     * Constructor.
     */      
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }      
    
    /**
     * {@inheritdoc}
     */  
    public function getId()
    {
        return $this->id;
    } 

    /**
     * {@inheritdoc}
     */
    public function isRoot()
    {
        return !$this->getParent();
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(CategoryInterface $parent = null)
    {
        $this->parent = $parent;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }    

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
        
        return $this;
    }    

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addChild(CategoryInterface $child)
    {
        if (!$this->hasChild($child)) {
            $child->setParent($this);
            $this->children->add($child);
        }
        
        return $this;        
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeChild(CategoryInterface $child)
    {
        if ($this->hasChild($child)) {
            $this->children->removeElement($child);
            $child->setParent(null);
        }        
        
        return $this;        
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasChild(CategoryInterface $child)
    {
        return $this->children->contains($child);
    }

    /**
     * Returns the category string representation.
     * 
     * @return string
     */    
    public function __toString()
    {
        return (string) $this->getName();
    }       
}