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
 * Category.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class Category implements CategoryInterface
{
    /**
     * @var mixed
     */
    protected $id; 
    
    /**
     * @var string
     */
    protected $name; 
    
    /**
     * @var string
     */
    protected $slug;    

    /**
     * @var CategoryInterface
     */
    protected $parent;     
    
    /**
     * @var integer
     */
    protected $position;      

    /**
     * @var integer
     */
    protected $rootNode;    
    
    /**
     * @var integer
     */
    protected $leftNode;

    /**
     * @var integer
     */
    protected $rightNode;    
    
    /**
     * @var integer
     */
    protected $levelDepth;    
    
    /**
     * @var Collection
     */
    protected $children;    

    /**
     * @var \Datetime
     */
    protected $createdAt;

    /**
     * @var \Datetime
     */
    protected $updatedAt;    
    
    
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
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeChild(CategoryInterface $child)
    {
        if ($this->children->removeElement($child)) {
            $child->setParent(null);
        }        
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasChild(CategoryInterface $child)
    {
        return $this->children->contains($child);
    }

    /**
     * {@inheritdoc}
     */   
    public function getCreatedAt()
    {
        return $this->createdAt;
    }    
      
    /**
     * {@inheritdoc}
     */   
    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;        
    }    
    
    /**
     * {@inheritdoc}
     */   
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    } 
  
    /**
     * {@inheritdoc}
     */   
    public function setUpdatedAt(\Datetime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;        
    }     
    
    /**
     * Returns the category name.
     * 
     * @return string
     */    
    public function __toString()
    {
        return (string) $this->getName();
    }       
}