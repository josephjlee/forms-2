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
    // Module meta
    'meta' => array(
        'title'        => _a('Form'),
        'description'  => _a('form generator system'),
        'version'      => '0.0.3',
        'license'      => 'New BSD',
        'logo'         => 'image/logo.png',
        'readme'       => 'docs/readme.txt',
        'demo'         => 'http://pialog.org',
        'icon'         => 'fa-keyboard-o',
    ),
    // Dependency
    'dependency' => array(
        'tools',
        'apis',
        'user',
    ),
    // Author information
    'author' => array(
        'Name'         => 'Hossein Azizabadi',
        'email'        => 'azizabadi@faragostaresh.com',
        'website'      => 'http://pialog.org',
        'credits'      => 'Pi Engine Team'
    ),
    // Resource
    'resource' => array(
        'database'     => 'database.php',
        'navigation'   => 'navigation.php',
    ),
);
