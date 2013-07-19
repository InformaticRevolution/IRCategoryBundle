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

/**
 * Category interface.
 * 
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface CategoryInterface
{   
    /**
     * Returns the id.
     * 
     * @return mixed
     */
    public function getId();      
    
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();    
    
    /**
     * Sets the name.
     *
     * @param string $name
     * 
     * @return CategoryInterface
     */
    public function setName($name);
    
    /**
     * Returns the slug.
     *
     * @return string
     */
    public function getSlug();    
    
    /**
     * Sets the slug.
     *
     * @param string $slug
     * 
     * @return CategoryInterface
     */
    public function setSlug($slug);    

    /**
     * Checks whether category is root.
     *
     * @return Boolean
     */
    public function isRoot();    
    
    /**
     * Returns the parent.
     *
     * @return CategoryInterface|null
     */
    public function getParent();

    /**
     * Sets the parent.
     *
     * @param CategoryInterface|null $parent
     * 
     * @return CategoryInterface
     */
    public function setParent(CategoryInterface $parent = null);     
    
    /**
     * Returns the position.
     *
     * @return integer
     */
    public function getPosition();

    /**
     * Sets the position.
     *
     * @param integer $position
     * 
     * @return CategoryInterface
     */
    public function setPosition($position);

    /**
     * Returns all chidren.
     *
     * @return \Traversable
     */
    public function getChildren(); 
    
    /**
     * Adds a child.
     *
     * @param CategoryInterface $child
     * 
     * @return CategoryInterface
     */
    public function addChild(CategoryInterface $child);
    
    /**
     * Removes a child.
     *
     * @param CategoryInterface $child
     * 
     * @return CategoryInterface
     */
    public function removeChild(CategoryInterface $child);    
    
    /**
     * Checks whether category has given child.
     *
     * @param CategoryInterface $child
     *
     * @return Boolean
     */
    public function hasChild(CategoryInterface $child);
    
    /**
     * Returns the creation time.
     *
     * @return \Datetime
     */
    public function getCreatedAt(); 
    
    /**
     * Sets the creation time.
     * 
     * @param \Datetime $datetime
     * 
     * @return CategoryInterface
     */
    public function setCreatedAt(\Datetime $datetime);     
    
    /**
     * Returns the last update time.
     *
     * @return \Datetime
     */
    public function getUpdatedAt();    
    
    /**
     * Sets the last update time.
     * 
     * @param \Datetime|null $datetime
     * 
     * @return CategoryInterface
     */
    public function setUpdatedAt(\Datetime $datetime = null);      
}

