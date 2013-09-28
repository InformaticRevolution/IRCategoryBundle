<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use IR\Bundle\CategoryBundle\IRCategoryEvents;
use IR\Bundle\CategoryBundle\Event\CategoryEvent;

/**
 * Controller managing the categories.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryController extends ContainerAware
{
    /**
     * List all the categories of given category.
     */
    public function listAction($parentId = null, $path = array())
    {
        $parent = $parentId ? $this->findCategoryById($parentId) : null;
        
        /* @var $categoryManager \IR\Bundle\CategoryBundle\Manager\CategoryManagerInterface */
        $categoryManager = $this->container->get('ir_category.manager.category');

        if (null !== $parent) {
            $path = $categoryManager->getPath($parent);
            $categories = $parent->getChildren();
        }
        else {
            $categories = $categoryManager->getRootCategories('position', 'asc');
        }

        return $this->container->get('templating')->renderResponse('IRCategoryBundle:Category:list.html.'.$this->getEngine(), array(
            'path' => $path,
            'parent' => $parent,
            'parentId' => $parentId,
            'categories' => $categories,
        ));
    }     
    
    /**
     * Create a new category: show the new form.
     */
    public function newAction(Request $request, $parentId = null)
    {               
        $parent = $parentId ? $this->findCategoryById($parentId) : null;

        /* @var $categoryManager \IR\Bundle\CategoryBundle\Manager\CategoryManagerInterface */
        $categoryManager = $this->container->get('ir_category.manager.category');
        $category = $categoryManager->createCategory($parent);
        
        $form = $this->container->get('ir_category.form.category'); 
        $form->setData($category);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $categoryManager->updateCategory($category);
            
            /* @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
            $dispatcher = $this->container->get('event_dispatcher');                
            $dispatcher->dispatch(IRCategoryEvents::CATEGORY_CREATE_COMPLETED, new CategoryEvent($category));
                
            return new RedirectResponse($this->container->get('router')->generate('ir_category_list', array('parentId' => $parentId)));                      
        }
        
        return $this->container->get('templating')->renderResponse('IRCategoryBundle:Category:new.html.'.$this->getEngine(), array(
            'parentId' => $parentId,
            'form' => $form->createView(),
        ));          
    }
  
    /**
     * Edit a category: show the edit form.
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->findCategoryById($id);
        $parentId = $category->getParent() ?: $category->getParent()->getId();
        
        $form = $this->container->get('ir_category.form.category');      
        $form->setData($category);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->container->get('ir_category.manager.category')->updateCategory($category);
            
            /* @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
            $dispatcher = $this->container->get('event_dispatcher');              
            $dispatcher->dispatch(IRCategoryEvents::CATEGORY_EDIT_COMPLETED, new CategoryEvent($category));
                        
            return new RedirectResponse($this->container->get('router')->generate('ir_category_list', array('parentId' => $parentId)));                     
        }        
        
        return $this->container->get('templating')->renderResponse('IRCategoryBundle:Category:edit.html.'.$this->getEngine(), array(
            'category' => $category,
            'parentId' => $parentId,
            'form' => $form->createView(),
        ));          
    }    
    
    /**
     * Delete a category.
     */
    public function deleteAction($id)
    {
        $category = $this->findCategoryById($id);
        $parentId = !$category->isRoot() ? $category->getParent()->getId() : null;
        
        $this->container->get('ir_category.manager.category')->deleteCategory($category);
        
        /* @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');          
        $dispatcher->dispatch(IRCategoryEvents::CATEGORY_DELETE_COMPLETED, new CategoryEvent($category));
                
        return new RedirectResponse($this->container->get('router')->generate('ir_category_list', array('parentId' => $parentId)));  
    }      
    
    /**
     * Move a category.
     */    
    public function moveAction(Request $request, $id)
    {   
        $category = $this->findCategoryById($id);
        $parentId = !$category->isRoot() ? $category->getParent()->getId() : null;
        
        if ($request->request->has('position')) {
            $category->setPosition($request->request->get('position'));
            $this->container->get('ir_category.manager.category')->updateCategory($category);
        }
        
        if ($request->isXmlHttpRequest()) {
            $response = new Response(json_encode(array('success' => true)));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
               
        return new RedirectResponse($this->container->get('router')->generate('ir_category_list', array('parentId' => $parentId))); 
    }

    /**
     * Finds a category by id.
     *
     * @param mixed $id
     *
     * @return CategoryInterface
     * 
     * @throws NotFoundHttpException When category does not exist
     */
    protected function findCategoryById($id)
    {
        $category = $this->container->get('ir_category.manager.category')->findCategoryBy(array('id' => $id));

        if (null === $category) {
            throw new NotFoundHttpException(sprintf('The category with id %s does not exist', $id));
        }

        return $category;
    }    

    /**
     * Returns the template engine.
     * 
     * @return string
     */    
   protected function getEngine()
    {
        return $this->container->getParameter('ir_category.template.engine');
    }    
}
