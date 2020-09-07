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
    'category' => [
        [
            'name'  => 'image',
            'title' => _t('Image'),
        ],
        [
            'name'  => 'notification',
            'title' => _t('Notification'),
        ],
    ],

    'item' => [
        // Image
        'image_ratio_w' => [
            'category'    => 'image',
            'title'       => _t('Image ratio width'),
            'description' => _t('Example : "4" for 4/1 ratio'),
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 4,
        ],
        'image_ratio_h' => [
            'category'    => 'image',
            'title'       => _t('Image ratio height'),
            'description' => _t('Example : "1" for 4/1 ratio'),
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        // Notification
        'notification_desc'   => [
            'category'    => 'notification',
            'title'       => _a('Description for sent notification'),
            'description' => '',
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => '',
        ],
    ],
];
