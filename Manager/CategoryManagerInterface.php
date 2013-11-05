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
 * Category Manager Interface.
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
     */
    public function updateCategory(CategoryInterface $category);    
         
    /**
     * Deletes a category.
     *
     * @param CategoryInterface $category
     */
    public function deleteCategory(CategoryInterface $category);    

    /**
     * Finds a category by given criteria.
     *
     * @param array $criteria
     *
     * @return CategoryInterface|null
     */
    public function findCategoryBy(array $criteria);    
    
    /**
     * Finds categories by given criteria.
     * 
     * @param array      $criteria
     * @param array|null $orderBy
     * 
     * @return array
     */
    public function findCategoriesBy(array $criteria, array $orderBy = null);
    
    /**
     * Returns the list of children categories by given node.
     * 
     * @param CategoryInterface|null $category
     * @param array|null             $orderBy
     * 
     * @return array
     */
    public function getChildrenCategories(CategoryInterface $category = null, array $orderBy = null);
    
    /**
     * Returns the categories hierarchy by given category.
     *
     * @param CategoryInterface|null $category
     * @param Boolean                $directChildrenOnly
     * @param Boolean                $includeCategory
     *
     * @return array
     */
    public function getCategoriesHierarchy(CategoryInterface $category = null, $directChildrenOnly = false, $includeCategory = false);    
    
    /**
     * Returns the tree path of categories by given category.
     *
     * @param CategoryInterface $category
     * 
     * @return array
     */
    public function getCategoryPath(CategoryInterface $category);

    /**
     * Returns the category's fully qualified class name.
     *
     * @return string
     */
    public function getClass(); 
}

