<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\DependencyInjection;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IR\Bundle\CategoryBundle\DependencyInjection\IRCategoryExtension;

/**
 * Category Extension Test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class IRCategoryExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** 
     * @var ContainerBuilder
     */
    protected $configuration;
    
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testCategoryLoadThrowsExceptionUnlessDatabaseDriverSet()
    {
        $loader = new IRCategoryExtension();
        $config = $this->getEmptyConfig();
        unset($config['db_driver']);
        $loader->load(array($config), new ContainerBuilder());
    }   
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testCategoryLoadThrowsExceptionUnlessDatabaseDriverIsValid()
    {
        $loader = new IRCategoryExtension();
        $config = $this->getEmptyConfig();
        $config['db_driver'] = 'foo';
        $loader->load(array($config), new ContainerBuilder());
    }    
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testCategoryLoadThrowsExceptionUnlessCategoryModelClassSet()
    {
        $loader = new IRCategoryExtension();
        $config = $this->getEmptyConfig();
        unset($config['category_class']);
        $loader->load(array($config), new ContainerBuilder());
    }    
    
    public function testDisableCategory()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new IRCategoryExtension();
        $config = $this->getEmptyConfig();
        $config['category'] = false;
        $loader->load(array($config), $this->configuration);
        $this->assertNotHasDefinition('ir_category.form.category');
    }    
    
    public function testCategoryLoadModelClassWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('Acme\CategoryBundle\Entity\Category', 'ir_category.model.category.class');
    }    
    
    public function testCategoryLoadModelClass()
    {
        $this->createFullConfiguration();

        $this->assertParameter('Acme\CategoryBundle\Entity\Category', 'ir_category.model.category.class');
    }    
    
    public function testCategoryLoadManagerClassWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('orm', 'ir_category.db_driver');
        $this->assertAlias('ir_category.manager.category.default', 'ir_category.manager.category');
    }    
    
    public function testCategoryLoadManagerClass()
    {
        $this->createFullConfiguration();

        $this->assertParameter('orm', 'ir_category.db_driver');
        $this->assertAlias('acme_category.manager.category', 'ir_category.manager.category');
    }    

    public function testCategoryLoadFormClassWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('ir_category', 'ir_category.form.type.category');
    }    

    public function testCategoryLoadFormClass()
    {
        $this->createFullConfiguration();

        $this->assertParameter('acme_category', 'ir_category.form.type.category');
    }    
    
    public function testCategoryLoadFormNameWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('ir_category_form', 'ir_category.form.name.category');
    }

    public function testCategoryLoadFormName()
    {
        $this->createFullConfiguration();

        $this->assertParameter('acme_category_form', 'ir_category.form.name.category');
    }    
    
    public function testCategoryLoadFormServiceWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertHasDefinition('ir_category.form.category');
    }    
    
    public function testCategoryLoadFormService()
    {
        $this->createFullConfiguration();

        $this->assertHasDefinition('ir_category.form.category');
    }    
    
    public function testCategoryLoadTemplateConfigWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('twig', 'ir_category.template.engine');
    }    
    
    public function testCategoryLoadTemplateConfig()
    {
        $this->createFullConfiguration();

        $this->assertParameter('php', 'ir_category.template.engine');
    }    
    
    protected function createEmptyConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new IRCategoryExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }    
    
    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new IRCategoryExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }    
        
    /**
     * @return array
     */
    protected function getEmptyConfig()
    {
        $parser = new Parser();

        return $parser->parse(file_get_contents(__DIR__.'/Fixtures/minimal_config.yml'));
    } 
    
    /**
     * @return array
     */    
    protected function getFullConfig()
    {
        $parser = new Parser();

        return $parser->parse(file_get_contents(__DIR__.'/Fixtures/full_config.yml'));
    }   

    /**
     * @param string $value
     * @param string $key
     */
    private function assertAlias($value, $key)
    {
        $this->assertEquals($value, (string) $this->configuration->getAlias($key), sprintf('%s alias is correct', $key));
    }    
    
    /**
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }    
    
    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }    
    
    /**
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertFalse(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }    
    
    protected function tearDown()
    {
        unset($this->configuration);
    }    
}
