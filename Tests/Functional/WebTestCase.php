<?php

/*
 * This file is part of the IRCategoryBundle package.
 * 
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle\Tests\Functional;

use Nelmio\Alice\Fixtures;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * Web Test Case.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * Creates a fresh database.
     */
    protected final function importDatabaseSchema()
    {        
        $em = $this->getEntityManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        
        if (!empty($metadata)) {
            $schemaTool = new SchemaTool($em);
            $schemaTool->dropDatabase();
            $schemaTool->createSchema($metadata);
        }        
    }    
    
    /**
     * Loads fixtures into the database.
     * 
     * @return array
     */    
    protected function loadFixtures()
    {        
        return Fixtures::load(__DIR__.'/Fixtures/category.yml', $this->getEntityManager());       
    }     
    
    /**
     * Returns doctrine orm entity manager.
     * 
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {        
        return static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }    
    
    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/IRCategoryBundle/');
    }     
}
