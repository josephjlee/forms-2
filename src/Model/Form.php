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

namespace Module\Forms\Model;

use Pi\Application\Model\Model;

class Form extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id',
        'title',
        'slug',
        'description',
        'status',
        'time_create',
        'time_start',
        'time_end',
        'type',
        'count',
    );
}