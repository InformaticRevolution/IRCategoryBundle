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

use IR\Bundle\CategoryBundle\Model\Category;

/**
 * Category Test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $category = $this->getCategory();
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $category->getChildren());
    }
            
    public function testAddChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategory();
        
        $this->assertNotContains($child, $category->getChildren());
        $this->assertNull($child->getParent());
        
        $category->addChild($child);
        
        $this->assertContains($child, $category->getChildren());
        $this->assertSame($category, $child->getParent());        
    }      
    
    public function testRemoveChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategory();
        $category->addChild($child);
        
        $this->assertContains($child, $category->getChildren());
        $this->assertSame($category, $child->getParent());  
        
        $category->removeChild($child);
        
        $this->assertNotContains($child, $category->getChildren());
        $this->assertNull($child->getParent());
    }    
    
    public function testHasChild()
    {
        $category = $this->getCategory();
        $child = $this->getCategory();
        
        $this->assertFalse($category->hasChild($child));
        $category->addChild($child);
        $this->assertTrue($category->hasChild($child));
    }    
    
    /**
     * @dataProvider getSimpleTestData
     */
    public function testSimpleSettersGetters($property, $value, $default)
    {
        $getter = 'get'.$property;
        $setter = 'set'.$property;
        
        $category = $this->getCategory();
        
        $this->assertEquals($default, $category->$getter());
        $category->$setter($value);
        $this->assertEquals($value, $category->$getter());
    }
    
    public function getSimpleTestData()
    {
        return array(
            array('name', 'Category 1', null),
            array('slug', 'category-1', null),
            array('permalink', 'category/subcategory', null),
            array('parent', $this->getCategory(), null),
            array('position', 2, null),
            array('createdAt', new \DateTime(), null),
            array('updatedAt', new \DateTime(), null),            
        );
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
}
