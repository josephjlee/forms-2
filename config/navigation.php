<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return [
    'front' => false,
    'admin' => [
        'form'    => [
            'label'      => _a('List of forms'),
            'route'      => 'admin',
            'module'     => 'forms',
            'controller' => 'form',
            'action'     => 'index',
            'pages'      => [
                'support' => [
                    'label'      => _a('List of forms'),
                    'route'      => 'admin',
                    'module'     => 'forms',
                    'controller' => 'form',
                    'action'     => 'index',
                ],
                'update'  => [
                    'label'      => _a('New form'),
                    'route'      => 'admin',
                    'module'     => 'forms',
                    'controller' => 'form',
                    'action'     => 'update',
                ],
            ],
        ],
        'element' => [
            'label'      => _a('Form elements'),
            'route'      => 'admin',
            'module'     => 'forms',
            'controller' => 'element',
            'action'     => 'index',
            'pages'      => [
                'support' => [
                    'label'      => _a('Form elements'),
                    'route'      => 'admin',
                    'module'     => 'forms',
                    'controller' => 'element',
                    'action'     => 'index',
                ],
                'update'  => [
                    'label'      => _a('New element'),
                    'route'      => 'admin',
                    'module'     => 'forms',
                    'controller' => 'element',
                    'action'     => 'update',
                ],
            ],
        ],
        'record'  => [
            'label'      => _a('Form records'),
            'route'      => 'admin',
            'module'     => 'forms',
            'controller' => 'record',
            'action'     => 'index',
        ],
    ],
];
