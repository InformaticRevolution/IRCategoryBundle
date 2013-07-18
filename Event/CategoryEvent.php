<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use IR\Bundle\CategoryBundle\Model\CategoryInterface;

/**
 * Category event.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryEvent extends Event
{
    /**
     * @var CategoryInterface
     */        
    protected $category;
    
    
   /**
    * Constructor.
    *
    * @param CategoryInterface $category
    */         
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the category.
     * 
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }
}