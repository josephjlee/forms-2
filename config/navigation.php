<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return array(
    'front' => false,
    'admin' => array(
        'form' => array(
            'label'        => _a('List of forms'),
            'route'        => 'admin',
            'module'       => 'forms',
            'controller'   => 'form',
            'action'       => 'index',
            'pages' => array(
                'support' => array(
                    'label'        => _a('List of forms'),
                    'route'        => 'admin',
                    'module'       => 'forms',
                    'controller'   => 'form',
                    'action'       => 'index',
                ),
                'update' => array(
                    'label'        => _a('New form'),
                    'route'        => 'admin',
                    'module'       => 'forms',
                    'controller'   => 'form',
                    'action'       => 'update',
                ),
            ),
        ),
        'element' => array(
            'label'        => _a('Form elements'),
            'route'        => 'admin',
            'module'       => 'forms',
            'controller'   => 'element',
            'action'       => 'index',
            'pages' => array(
                'support' => array(
                    'label'        => _a('Form elements'),
                    'route'        => 'admin',
                    'module'       => 'forms',
                    'controller'   => 'element',
                    'action'       => 'index',
                ),
                'update' => array(
                    'label'        => _a('New element'),
                    'route'        => 'admin',
                    'module'       => 'forms',
                    'controller'   => 'element',
                    'action'       => 'update',
                ),
            ),
        ),
    ),
);