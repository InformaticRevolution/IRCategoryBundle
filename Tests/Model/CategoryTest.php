<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use IR\Bundle\CategoryBundle\Model\Category;

/**
 * Category test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testId()
    {
        $category = $this->getCategory();
        
        $this->assertNull($category->getId());
    }
    
    public function testName()
    {
        $category = $this->getCategory();
        
        $this->assertNull($category->getName());
        $category->setName('Category 1');
        $this->assertEquals('Category 1', $category->getName());
    }
    
    public function testSlug()
    {
        $category = $this->getCategory();
        
        $this->assertNull($category->getSlug());
        $category->setSlug('category-1');
        $this->assertEquals('category-1', $category->getSlug());
    }  
    
    public function testParent()
    {
        $category = $this->getCategory();
        $parent = $this->getCategory();
        
        $this->assertNull($category->getParent());
        $category->setParent($parent);
        $this->assertSame($parent, $category->getParent());
    } 
    
    public function testPosition()
    {
        $category = $this->getCategory();
        
        $this->assertNull($category->getPosition());
        $category->setPosition(2);
        $this->assertEquals(2, $category->getPosition());
    }     
    
    public function testChildren()
    {
        $category = $this->getCategory();
        
        $this->assertEquals(new ArrayCollection(), $category->getChildren());
    }
    
    public function testAddChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategoryMock();
        
        $this->assertNotContains($child, $category->getChildren());
        $category->addChild($child);
        $this->assertContains($child, $category->getChildren());
    }
    
    public function testAddChildSetParent()
    {
        $category = $this->getCategory();
        $child = $this->getCategoryMock();
        
        $child->expects($this->once())
            ->method('setParent')
            ->with($this->equalTo($category));
        
        $category->addChild($child);
    }
    
    public function testRemoveChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategoryMock();
        $category->addChild($child);
        
        $this->assertContains($child, $category->getChildren());
        $category->removeChild($child);
        $this->assertNotContains($child, $category->getChildren());
    }    
    
    public function testHasChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategoryMock();
        
        $this->assertFalse($category->hasChild($child));
        $category->addChild($child);
        $this->assertTrue($category->hasChild($child));
    }
    
    public function testCreatedAt()
    {
        $category = $this->getCategory();
        $datetime = new \DateTime();
        
        $this->assertNull($category->getCreatedAt());
        $category->setCreatedAt($datetime);
        $this->assertSame($datetime, $category->getCreatedAt());
    } 
    
    public function testUpdatedAt()
    {
        $category = $this->getCategory();
        $datetime = new \DateTime();
        
        $this->assertNull($category->getUpdatedAt());
        $category->setUpdatedAt($datetime);
        $this->assertSame($datetime, $category->getUpdatedAt());
    }       
    
    public function testToString()
    {
        $category = $this->getCategory();
        
        $this->assertEquals('', $category);
        $category->setName('Category 1');
        $this->assertEquals('Category 1', $category);
    }
            
    /**
     * @return Category
     */
    protected function getCategory()
    {
        return $this->getMockForAbstractClass('IR\Bundle\CategoryBundle\Model\Category');
    }
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCategoryMock()
    {
        return $this->getMock('IR\Bundle\CategoryBundle\Model\CategoryInterface');
    }
}
