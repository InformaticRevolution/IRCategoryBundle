<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Manager;

use IR\Bundle\CategoryBundle\Model\CategoryInterface;

/**
 * Category manager interface.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface CategoryManagerInterface
{   
    /**
     * Creates an empty category instance.
     *
     * @param CategoryInterface|null $parent
     * 
     * @return CategoryInterface
     */    
    public function createCategory(CategoryInterface $parent = null);
    
    /**
     * Updates a category.
     *
     * @param CategoryInterface $category
     * 
     * @return void
     */
    public function updateCategory(CategoryInterface $category);    
         
    /**
     * Deletes a category.
     *
     * @param CategoryInterface $category
     * 
     * @return void
     */
    public function deleteCategory(CategoryInterface $category);    

    /**
     * Finds a category by the given criteria.
     *
     * @param array $criteria
     *
     * @return CategoryInterface|null
     */
    public function findCategoryBy(array $criteria);    
    
    /**
     * Returns all root categories.
     *
     * @param string $sortByField
     * @param string $direction
     * 
     * @return array
     */
    public function getRootCategories($sortByField = null, $direction = 'asc'); 
    
    /**
     * Returns the tree path of categories by given category.
     *
     * @param CategoryInterface $category
     * 
     * @return array
     */
    public function getPath(CategoryInterface $category);

    /**
     * Returns the categories's fully qualified class name.
     *
     * @return string
     */
    public function getClass(); 
}

