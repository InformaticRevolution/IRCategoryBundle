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
 * Category Manager Test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryManagerTest extends \PHPUnit_Framework_TestCase
{
    const CATEGORY_CLASS = 'IR\Bundle\CategoryBundle\Tests\TestCategory';
 
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
        $category = $this->categoryManager->createCategory();
        
        $this->assertInstanceOf(static::CATEGORY_CLASS, $category);
    }
    
    public function testGetChildrenCategories()
    {
        $category = $this->getCategoryMock();
        $orderBy = array('foo' => 'bar');
        
        $this->categoryManager->expects($this->once())
            ->method('findCategoriesBy')
            ->with($this->equalTo(array('parent' => $category)), $this->equalTo($orderBy))
            ->will($this->returnValue(array()));
        
        $this->categoryManager->getChildrenCategories($category, $orderBy);
    }
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCategoryMock()
    {
        return $this->getMock('IR\Bundle\CategoryBundle\Model\CategoryInterface');
    }
}