<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Entity;

use IR\Bundle\CategoryBundle\Model\Category as AbstractCategory;

/**
 * Entity category implementation.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class Category extends AbstractCategory
{
    /**
     * @var integer
     */
    protected $root;    
    
    /**
     * @var integer
     */
    protected $left;

    /**
     * @var integer
     */
    protected $right;    
    
    /**
     * @var integer
     */
    protected $level;
}