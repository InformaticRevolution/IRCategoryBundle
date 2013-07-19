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
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }    

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $categories = $this->categoryManager->getChildrenCategories(null, false, array('root', 'position'));
        
        $resolver
            ->setDefaults(array(
                'choice_list' => new ObjectChoiceList($categories),
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