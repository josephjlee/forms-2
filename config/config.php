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
            'name'  => 'review',
            'title' => _t('Review'),
        ],
        [
            'name'  => 'notification',
            'title' => _t('Notification'),
        ],
    ],

    'item' => [
        // Image
        'main_image_height'             => [
            'category'    => 'image',
            'title'       => _a('Main Image resize height'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1200,
        ],
        'main_image_width'             => [
            'category'    => 'image',
            'title'       => _a('Main Image resize width'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 250,
        ],
        'list_image_height'             => [
            'category'    => 'image',
            'title'       => _a('Image resize height for list'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 600,
        ],
        'list_image_width'             => [
            'category'    => 'image',
            'title'       => _a('Image resize width for list'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 125,
        ],

        // Review
        'review_action'            => [
            'category'    => 'review',
            'title'       => _a('Review Action'),
            'description' => _a('Use `|` as delimiter to separate terms'),
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => '|shop_allowOrder',
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
