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

use Symfony\Component\BrowserKit\Client;
use IR\Bundle\CategoryBundle\Tests\Functional\WebTestCase;

/**
 * Category Controller Test.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class CategoryControllerTest extends WebTestCase
{   
    const FORM_INTENTION = 'category';
    
    /**
     * @var Client 
     */
    private $client;


    protected function setUp()
    {
        $this->client = static::createClient();
        $this->importDatabaseSchema();
        $this->loadFixtures();
    }

    public function testListAction()
    {
        $crawler = $this->client->request('GET', '/admin/categories/');

        $this->assertResponseStatusCode(200);
        $this->assertCount(6, $crawler->filter('ul.list-group li'));
    }
    
    public function testListActionWithParentCategory()
    {
        $crawler = $this->client->request('GET', '/admin/categories/?parentId=1');

        $this->assertResponseStatusCode(200);
        $this->assertCount(3, $crawler->filter('ul.list-group li'));
    }       
    
    public function testShowAction()
    {
        $this->client->request('GET', '/admin/categories/1');
        
        $this->assertResponseStatusCode(200);
    }      
    
    public function testNewActionGetMethod()
    {
        $crawler = $this->client->request('GET', '/admin/categories/new');
        
        $this->assertResponseStatusCode(200);
        $this->assertCount(1, $crawler->filter('form'));
    }
    
    public function testNewActionPostMethod()
    {        
        $this->client->request('POST', '/admin/categories/new', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(static::FORM_INTENTION),
            ) 
        ));  
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/admin/categories/');
        $this->assertCount(7, $crawler->filter('ul.list-group li'));
        $this->assertRegExp('~Category 1~', $crawler->filter('ul.list-group')->text());        
    }
    
    public function testNewActionPostMethodWithParentCategory()
    {        
        $this->client->request('POST', '/admin/categories/new?parentId=1', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(static::FORM_INTENTION),
            ) 
        ));  
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/admin/categories/?parentId=1');
        $this->assertCount(4, $crawler->filter('ul.list-group li'));
        $this->assertRegExp('~Category 1~', $crawler->filter('ul.list-group')->text());        
    }    
    
    public function testEditActionGetMethod()
    {   
        $crawler = $this->client->request('GET', '/admin/categories/1/edit');
        
        $this->assertResponseStatusCode(200);
        $this->assertCount(1, $crawler->filter('form'));        
    }
    
    public function testEditActionPostMethod()
    {        
        $this->client->request('POST', '/admin/categories/1/edit', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(static::FORM_INTENTION),
            ) 
        ));     
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/admin/categories/');
        $this->assertCount(6, $crawler->filter('ul.list-group li'));
        $this->assertRegExp('~Category 1~', $crawler->filter('ul.list-group')->text());      
    }
    
    public function testEditActionPostMethodWithParentCategory()
    {        
        $this->client->request('POST', '/admin/categories/7/edit', array(
            'ir_category_form' => array (
                'name' => 'Category 1',
                '_token' => $this->generateCsrfToken(static::FORM_INTENTION),
            ) 
        ));  
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/admin/categories/?parentId=1');
        $this->assertCount(3, $crawler->filter('ul.list-group li'));
        $this->assertRegExp('~Category 1~', $crawler->filter('ul.list-group')->text());        
    }      
    
    public function testDeleteAction()
    {
        $this->client->request('GET', '/admin/categories/1/delete');
        
        $this->assertResponseStatusCode(302);
        
        $crawler = $this->client->followRedirect();
        
        $this->assertResponseStatusCode(200);
        $this->assertCurrentUri('/admin/categories/');
        $this->assertCount(5, $crawler->filter('ul.list-group li'));
    }     

    public function testMoveAction()
    {        
        $this->client->request('GET', '/admin/categories/1/move?position=3');

        $this->assertResponseStatusCode(200);
        $this->assertResponseContentType('application/json');
        $this->assertJsonResponseContent(json_encode(array('success' => true)));
        
        $crawler = $this->client->request('GET', '/admin/categories/');
        
        $this->assertEquals(array(2, 3, 4, 1, 5, 6), $crawler->filter('ul.list-group li')->extract('data-category'));
    }    
    
    public function testNotFoundHttpWhenCategoryNotExist()
    {   
        $this->client->request('GET', '/admin/categories/?parentId=foo');
        $this->assertResponseStatusCode(404); 
        
        $this->client->request('GET', '/admin/categories/foo');
        $this->assertResponseStatusCode(404);         
        
        $this->client->request('GET', '/admin/categories/new/foo');
        $this->assertResponseStatusCode(404);        
        
        $this->client->request('GET', '/admin/categories/foo/edit');
        $this->assertResponseStatusCode(404);
        
        $this->client->request('GET', '/admin/categories/foo/delete');
        $this->assertResponseStatusCode(404); 
        
        $this->client->request('GET', '/admin/categories/foo/move');
        $this->assertResponseStatusCode(404);         
    }  
    
    /**
     * Generates a CSRF token.
     * 
     * @param string $intention
     * 
     * @return string
     */
    protected function generateCsrfToken($intention)
    {
        return $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken($intention);
    }      
    
    /**
     * @param integer $statusCode
     */
    protected function assertResponseStatusCode($statusCode)
    {
        $this->assertEquals($statusCode, $this->client->getResponse()->getStatusCode());
    }   
    
    /**
     * @param integer $contentType
     */
    protected function assertResponseContentType($contentType)
    {
        $this->assertEquals($contentType, $this->client->getResponse()->headers->get('content-type'));
    } 
    
    /**
     * @param integer $contentType
     */
    protected function assertJsonResponseContent($content)
    {
        $this->assertJsonStringEqualsJsonString($content, $this->client->getResponse()->getContent());
    }     
    
    /**
     * @param string $uri
     */
    protected function assertCurrentUri($uri)
    {
        $this->assertStringEndsWith($uri, $this->client->getHistory()->current()->getUri());
    }
}
