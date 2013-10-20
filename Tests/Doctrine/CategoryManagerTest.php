<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Doctrine;

use IR\Bundle\CategoryBundle\Doctrine\CategoryManager;

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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;
    
    
    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }  
                
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
                
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::CATEGORY_CLASS))
            ->will($this->returnValue($this->repository));        

        $this->objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::CATEGORY_CLASS))
            ->will($this->returnValue($class));        
        
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::CATEGORY_CLASS));        
        
        $this->categoryManager = new CategoryManager($this->objectManager, static::CATEGORY_CLASS);
    }    

    public function testUpdateCategory()
    {
        $category = $this->getCategoryMock();
        
        $this->objectManager->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($category));
        
        $this->objectManager->expects($this->once())
            ->method('flush');

        $this->categoryManager->updateCategory($category);
    }
    
    public function testDeleteCategory()
    {
        $category = $this->getCategoryMock();
        
        $this->objectManager->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($category));
        
        $this->objectManager->expects($this->once())
            ->method('flush');

        $this->categoryManager->deleteCategory($category);
    }      
    
    public function testFindCategoryBy()
    {
        $criteria = array("foo" => "bar");
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue(array()));

        $this->categoryManager->findCategoryBy($criteria);
    }

    public function testGetClass()
    {
        $this->assertEquals(static::CATEGORY_CLASS, $this->categoryManager->getClass());
    }
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCategoryMock()
    {
        return $this->getMock('IR\Bundle\CategoryBundle\Model\CategoryInterface');
    }
}
