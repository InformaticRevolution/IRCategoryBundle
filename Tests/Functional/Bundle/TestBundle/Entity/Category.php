<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Functional\Bundle\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IR\Bundle\CategoryBundle\Model\Category as BaseCategory;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class Category extends BaseCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id; 
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     */
    protected $children;    
}
