<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Manager;

use IR\Bundle\CategoryBundle\Manager\CategoryManager;

/**
 * Category manager test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryManagerTest extends \PHPUnit_Framework_TestCase
{
    const CATEGORY_CLASS = 'IR\Bundle\CategoryBundle\Model\Category';
 
    /**
     * @var CategoryManager
     */
    protected $categoryManager;    
    
    
    public function setUp()
    {
        $this->categoryManager = $this->getMockForAbstractClass('IR\Bundle\CategoryBundle\Manager\CategoryManager');
        
        $this->categoryManager->expects($this->any())
            ->method('getClass')
            ->will($this->returnValue(static::CATEGORY_CLASS));        
    }
    
    public function testCreateCategory()
    {        
        $parent = $this->getCategoryMock();
        $category = $this->categoryManager->createCategory($parent);
        
        $this->assertInstanceOf(static::CATEGORY_CLASS, $category);
        $this->assertSame($parent, $category->getParent());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCategoryMock()
    {
        return $this->getMock('IR\Bundle\CategoryBundle\Model\CategoryInterface');
    }
}