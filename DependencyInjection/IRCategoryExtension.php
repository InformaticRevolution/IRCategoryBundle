<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Category extension.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class IRCategoryExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load(sprintf('driver/%s/category.xml', $config['db_driver']));
        
        foreach (array('listeners') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }  
        
        $container->setParameter('ir_product.db_driver', $config['db_driver']);
        $container->setParameter('ir_category.model.category.class', $config['category_class']);
        $container->setParameter('ir_category.template.engine', $config['template']['engine']);
        $container->setParameter('ir_category.backend_type_' . $config['db_driver'], true);
        
        $container->setAlias('ir_category.manager.category', $config['category_manager']);
        
        if (!empty($config['category'])) {
            $this->loadCategory($config['category'], $container, $loader);
        }        
    }
    
    private function loadCategory(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {        
        $loader->load('category.xml');
        
        $container->setParameter('ir_category.form.name.category', $config['form']['name']);
        $container->setParameter('ir_category.form.type.category', $config['form']['type']);
    }    
}
