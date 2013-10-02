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
use Symfony\Component\BrowserKit\Client;
use IR\Bundle\CategoryBundle\Tests\Functional\WebTestCase;

/**
 * Category controller test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryControllerTest extends WebTestCase
{   
    /**
     * @var Client 
     */
    private $client;


    protected function setUp()
    {
        $this->client = self::createClient();
        $this->importDatabaseSchema();
        $this->loadFixtures();
    }
            
    public function testListAction()
    {
        $crawler = $this->client->request('GET', '/categories/list');

        $this->assertResponseStatusCode(200);
        $this->assertCount(3, $crawler->filter('table tbody tr'));
    }    
        
    public function testNewActionGetMethod()
    {
        $crawler = $this->client->request('GET', '/categories/new');
        
        $this->assertResponseStatusCode(200);
        $this->assertCount(1, $crawler->filter('form'));
    }
    
    public function testNewActionPostMethod()
    {        
        $this->client->request('POST', '/categories/new', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(),
            ) 
        ));  
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/categories/list');
        $this->assertCount(4, $crawler->filter('table tbody tr'));
        $this->assertRegExp('~Category 1~', $crawler->filter('table tbody')->text());        
    }
    
    public function testNewActionGetMethodWithParentCategory()
    {
        $this->client->request('GET', '/categories/new/1');
        
        $this->assertResponseStatusCode(200);
    }   
    
    public function testNewActionPostMethodWithParentCategory()
    {        
        $this->client->request('POST', '/categories/new/1', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(),
            ) 
        ));  
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/categories/list/1');
        $this->assertCount(1, $crawler->filter('table tbody tr'));
        $this->assertRegExp('~Category 1~', $crawler->filter('table tbody')->text());        
    }    
    
    public function testEditActionGetMethod()
    {   
        $crawler = $this->client->request('GET', '/categories/1/edit');
        
        $this->assertResponseStatusCode(200);
        $this->assertCount(1, $crawler->filter('form'));        
    }
    
    public function testEditActionPostMethod()
    {        
        $this->client->request('POST', '/categories/1/edit', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(),
            ) 
        ));     
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/categories/list');
        $this->assertCount(3, $crawler->filter('table tbody tr'));
        $this->assertRegExp('~Category 1~', $crawler->filter('table tbody')->text());      
    }
    
    public function testDeleteAction()
    {
        $this->client->request('GET', '/categories/1/delete');
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertCurrentUri('/categories/list');
        $this->assertCount(2, $crawler->filter('table tbody tr'));
    }     
        
    /**
     * @param integer $statusCode
     */
    protected function assertResponseStatusCode($statusCode)
    {
        $this->assertEquals($statusCode, $this->client->getResponse()->getStatusCode());
    }    
    
    /**
     * @param string $uri
     */
    protected function assertCurrentUri($uri)
    {
        $this->assertStringEndsWith($uri, $this->client->getHistory()->current()->getUri());
    }
    
     /**
     * Generates a CSRF token.
     * 
     * @return string
     */
    protected function generateCsrfToken()
    {
        return $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('category');
    }

    /*
     * Loads the test fixtures into the database.
     */
    protected function loadFixtures()
    {
        Fixtures::load($this->getFixtures(), self::$kernel->getContainer()->get('doctrine.orm.entity_manager'));       
    }

    /**
     * Returns test fixtures.
     * 
     * @return array
     */
    protected function getFixtures()
    {
        return array(
            'IR\Bundle\CategoryBundle\Tests\Functional\Bundle\TestBundle\Entity\Category' => array(
                'category{1..3}' => array(
                    'name' => '<sentence(2)>',
                )
            )
        );
    }   
}
