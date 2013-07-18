<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IR\Bundle\CategoryBundle\Manager\CategoryManagerInterface;

/**
 * Category choice type.
 * 
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryChoiceType extends AbstractType
{
    /**
     * @var CategoryManagerInterface
     */         
    protected $categoryManager;

    
    /**
     * Constructor.
     * 
     * @param CategoryManagerInterface  $categoryManager
     */
    public function __construct(CategoryManagerInterface $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $categories = $this->categoryManager->getRootCategories();
        
        $choiceList = function (Options $options) use ($categories) {
            return new ObjectChoiceList($categories);
        };

        $resolver
            ->setDefaults(array(
                'choice_list' => $choiceList
            ))              
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ir_category_choice';
    }
}