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
 * Abstract Category Manager.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class CategoryManager implements CategoryManagerInterface
{
    /**
     * {@inheritdoc}
     */  
    public function createCategory(CategoryInterface $parent = null)
    {
        $class = $this->getClass();
        $category = new $class;
        $category->setParent($parent);
        
        return $category;
    } 
}
