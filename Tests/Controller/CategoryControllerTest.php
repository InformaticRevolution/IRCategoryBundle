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
        $client = static::createClient();
        $this->importDatabaseSchema();
        
        $crawler = $client->request('GET', '/admin/categories/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }    
}
