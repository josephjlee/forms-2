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
    // Module meta
    'meta'       => [
        'title'       => _a('Form'),
        'description' => _a('Multi forms generator system'),
        'version'     => '0.2.1',
        'license'     => 'New BSD',
        'logo'        => 'image/logo.png',
        'readme'      => 'docs/readme.txt',
        'demo'        => 'http://piengine.org',
        'icon'        => 'fa-keyboard',
    ],
    // Dependency
    'dependency' => [
        'user',
    ],
    // Author information
    'author'     => [
        'Name'    => 'Hossein Azizabadi',
        'email'   => 'azizabadi@faragostaresh.com',
        'website' => 'http://piengine.org',
        'credits' => 'Pi Engine Team',
    ],
    // Resource
    'resource'   => [
        'database'   => 'database.php',
        'config'        => 'config.php',
        'navigation' => 'navigation.php',
    ],
];
