Getting Started With IRCategoryBundle
=====================================

## Prerequisites

This version of the bundle requires Symfony 2.3+.

## Installation

1. Download IRCategoryBundle using composer
2. Enable the bundle
3. Create your Category class
4. Configure the IRCategoryBundle
5. Import IRCategoryBundle routing
6. Update your database schema
7. Enable the doctrine extensions

### Step 1: Download IRCategoryBundle using composer

Add IRCategoryBundle in your composer.json:

``` js
{
    "require": {
        "informaticrevolution/category-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update informaticrevolution/category-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IR\Bundle\CategoryBundle\IRCategoryBundle(),
    );
}
```

### Step 3: Create your Category class

**Warning:**

> If you override the __construct() method in your Category class, be sure
> to call parent::__construct(), as the base Category class depends on
> this to initialize some fields.

##### Annotations

``` php
<?php
// src/Acme/CategoryBundle/Entity/Category.php

namespace Acme\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use IR\Bundle\CategoryBundle\Model\Category as BaseCategory;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_category")
 */
class Category extends BaseCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     * @Gedmo\TreeParent
     * @Gedmo\SortableGroup
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     */
    protected $children;


    /**
     * Constructor.
     */  
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

##### Yaml or Xml

``` php
<?php
// src/Acme/CategoryBundle/Entity/Category.php

namespace Acme\CategoryBundle\Entity;

use IR\Bundle\CategoryBundle\Model\Category as BaseCategory;

/**
 * Category
 */
class Category extends BaseCategory
{
    /**
     * Constructor.
     */  
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

In YAML:

``` yaml
# src/Acme/CategoryBundle/Resources/config/doctrine/Category.orm.yml
Acme\CategoryBundle\Entity\Category:
    type:  entity
    table: acme_category
    id:
        id:
            type: integer
            generator:
                strategy: AUTO
    manyToOne:
        parent:
            targetEntity: Category
            inversedBy: children
            joinColumn:
                name: parent_id
                referencedColumnName: id
                onDelete: CASCADE
            gedmo:
                - treeParent
                - sortableGroup
    oneToMany:
        children:
            targetEntity: Category
            mappedBy: parent
            cascade: [ all ]
            orphanRemoval: true            
```

In XML:

``` xml
<!-- src/Acme/CategoryBundle/Resources/config/doctrine/Category.orm.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xmlns:gedmo="http://gediminasm.org/schemas/orm/doctrine-extensions-mapping"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

    <entity name="Acme\CategoryBundle\Entity\Category" table="acme_category">
        <id name="id" type="integer" column="id">
            <generator strategy="AUTO" />
        </id> 

        <many-to-one field="parent" target-entity="Category" inversed-by="children">
            <join-column name="parent_id" referenced-column-name="id" on-delete="CASCADE" />
            <gedmo:tree-parent />
            <gedmo:sortable-group />
        </many-to-one>   

        <one-to-many field="children" target-entity="Category" mapped-by="parent" orphan-removal="true">
            <cascade>
                <cascade-all />
            </cascade>            
        </one-to-many>
    </entity>
    
</doctrine-mapping>
```

### Step 4: Configure the IRCategoryBundle

Add the bundle minimum configuration to your `config.yml` file:

``` yaml
# app/config/config.yml
ir_category:
    db_driver: orm # orm is the only available driver for the moment 
    category_class: Acme\CategoryBundle\Entity\Category
```

### Step 5: Import IRCategoryBundle routing files

Add the following configuration to your `routing.yml` file:

``` yaml
# app/config/routing.yml
ir_category:
    resource: "@IRCategoryBundle/Resources/config/routing.xml"
    prefix: /admin/categories
```

### Step 6: Update your database schema

Run the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 7: Enable the doctrine extensions

**a) Enable the stof doctrine extensions bundle in the kernel**

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
    );
}
```

**b) Enable the tree, sortable and sluggable and timestampable extensions in your `config.yml` file**

``` yaml
# app/config/config.yml
stof_doctrine_extensions:
    orm:
        default:
            tree: true
            sortable: true
            sluggable: true
            timestampable: true