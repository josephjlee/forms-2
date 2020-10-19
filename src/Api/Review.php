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

namespace Module\Forms\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('review', 'forms')->postReview($form, $record, $date);
 */

class Review extends AbstractApi
{
    public function postReview($name, $form, $record, $date)
    {
        d($name);
        d($form);
        d($record);
        d($date);
    }
}