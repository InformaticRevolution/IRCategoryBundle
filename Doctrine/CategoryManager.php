<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use IR\Bundle\CategoryBundle\Model\CategoryInterface;
use IR\Bundle\CategoryBundle\Manager\CategoryManager as AbstractCategoryManager;

/**
 * Doctrine Category Manager.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryManager extends AbstractCategoryManager
{
    /**
     * @var ObjectManager
     */          
    protected $objectManager;
    
    /**
     * @var ObjectRepository
     */           
    protected $repository;    

    /**
     * @var string
     */           
    protected $class;  
    
    
   /**
    * Constructor.
    *
    * @param ObjectManager $om
    * @param string        $class
    */        
    public function __construct(ObjectManager $om, $class)
    {           
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }      
    
    /**
     * Updates a category.
     *
     * @param CategoryInterface $category
     * @param Boolean           $andFlush Whether to flush the changes (default true)
     */ 
    public function updateCategory(CategoryInterface $category, $andFlush = true)
    {  
        $this->objectManager->persist($category);
        
        if ($andFlush) {
            $this->objectManager->flush();
        }   
    }

    /**
     * {@inheritDoc}
     */     
    public function deleteCategory(CategoryInterface $category)
    {
        $this->objectManager->remove($category);
        $this->objectManager->flush();          
    }

    /**
     * {@inheritDoc}
     */
    public function findCategoryBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }    
    
    /**
     * {@inheritDoc}
     */
    public function findCategoriesBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findBy($criteria, $orderBy);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function getCategoriesHierarchy(CategoryInterface $category = null, $directChildrenOnly = false, $includeCategory = false)
    {
        return $this->repository->childrenHierarchy($category, $directChildrenOnly, array(), $includeCategory);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCategoryPath(CategoryInterface $category)
    {
        return $this->repository->getPath($category);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function getClass()
    {
        return $this->class;
    }
}
