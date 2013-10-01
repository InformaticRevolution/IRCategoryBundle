<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Controller;

use Nelmio\Alice\Fixtures;
use IR\Bundle\CategoryBundle\Tests\Functional\WebTestCase;

/**
 * Category controller test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryControllerTest extends WebTestCase
{   
    public function testList()
    {
        $client = self::createClient();
        $this->importDatabaseSchema();
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        Fixtures::load($this->getFixtures(), $em);
        
        $crawler = $client->request('GET', '/admin/categories/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('table tbody tr')->count());
    }    
    
    public function testListActionWithParentCategory()
    {
        $client = self::createClient();
        $this->importDatabaseSchema();
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        Fixtures::load($this->getFixtures(), $em);
        
        $crawler = $client->request('GET', '/admin/categories/list/1');  
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertEquals(2, $crawler->filter('table tbody tr')->count());
        
        $this->assertRegExp('~Category 4~', $crawler->filter('table tbody tr td')->text());
    }
          
    public function testNew()
    {
        $client = self::createClient();
        $this->importDatabaseSchema();
        
        $crawler = $client->request('GET', '/admin/categories/new');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('form'));
    }
    
    public function testNew2()
    {
        $client = self::createClient();
        $this->importDatabaseSchema();
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('category');
        
        $client->request('POST', '/admin/categories/new', array(
           'ir_category_form' => array (
               'name' => 'Category 1',
               '_token' => $csrfToken,
           ) 
        ));
        
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        
        $crawler = $client->followRedirect();
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('table tbody tr')->count());
        $this->assertRegExp('~Category 1~', $crawler->filter('table tbody tr td')->text());
    }
    
    public function testEdit()
    {
        $client = self::createClient();
        $this->importDatabaseSchema(); 
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        Fixtures::load($this->getFixtures(), $em);
                
        $crawler = $client->request('GET', '/admin/categories/1/edit');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('form'));        
    }
    
    public function testEdit2()
    {
        $client = self::createClient();
        $this->importDatabaseSchema(); 
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        Fixtures::load($this->getFixtures(), $em);
        
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('category');
        
        $client->request('POST', '/admin/categories/1/edit', array(
           'ir_category_form' => array (
               'name' => 'Category 1',
               '_token' => $csrfToken,
           ) 
        ));     
        
          $this->assertEquals(302, $client->getResponse()->getStatusCode());
        
        $crawler = $client->followRedirect();
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('table tbody tr')->count());
        $this->assertRegExp('~Category 1~', $crawler->filter('table tbody tr td')->text());      
    }
    
    public function testDelete()
    {
         $client = self::createClient();
        $this->importDatabaseSchema(); 
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        Fixtures::load($this->getFixtures(), $em); 
        
        $crawler = $client->request('GET', '/admin/categories/1/delete');
        
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        
        $crawler = $client->followRedirect();
        
        $this->assertEquals(2, $crawler->filter('table tbody tr')->count());
    }
            
    private function getFixtures()
    {
        return array(
            'IR\Bundle\CategoryBundle\Tests\Functional\Bundle\TestBundle\Entity\Category' => array(
                'category{1..3}' => array(
                    'name' => '<sentence(2)>',
                ),
                'category{4..6}' => array(
                    'name' => '<sentence(2)>',
                    'parent' => '@category1',
                )
            )
        );
    }
}
