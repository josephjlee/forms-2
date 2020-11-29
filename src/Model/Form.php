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

namespace Module\Forms\Model;

use Pi\Application\Model\Model;

class Form extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'title',
            'slug',
            'description',
            'status',
            'time_create',
            'time_start',
            'time_end',
            'count',
            'main_image',
            'register_need',
            'review_need',
            'show_answer',
            'multi_steps',
            'review_action',
        ];
}
