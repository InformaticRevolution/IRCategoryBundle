<?php

/*
 * This file is part of the IRCategoryBundle package.
 *
 * (c) Julien Kirsch <informatic.revolution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IR\Bundle\CategoryBundle;

/**
 * Contains all events thrown in the IRCategoryBundle.
 * 
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
final class IRCategoryEvents
{
    /**
     * The CATEGORY_CREATE_COMPLETED event occurs after saving the category in the category creation process.
     *
     * The event listener method receives a IR\Bundle\CategoryBundle\Event\CategoryEvent instance.
     */
    const CATEGORY_CREATE_COMPLETED = 'ir_category.category.create.completed';
    
    /**
     * The CATEGORY_EDIT_COMPLETED event occurs after saving the category in the category edit process.
     *
     * The event listener method receives a IR\Bundle\CategoryBundle\Event\CategoryEvent instance.
     */
    const CATEGORY_EDIT_COMPLETED = 'ir_category.category.edit.completed';
    
    /**
     * The CATEGORY_DELETE_COMPLETED event occurs after deleting the category.
     *
     * The event listener method receives a IR\Bundle\CategoryBundle\Event\CategoryEvent instance.
     */
    const CATEGORY_DELETE_COMPLETED = 'ir_category.category.delete.completed'; 
}