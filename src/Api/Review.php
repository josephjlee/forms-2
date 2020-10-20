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
 * Pi::api('review', 'forms')->postReview($form, $params);
 */

class Review extends AbstractApi
{
    public function postReview($name, $params)
    {
        $action = explode('_', $name);

        // Check module is active
        if (Pi::service('module')->isActive($action[0])) {

            // Set class namespace
            $class = sprintf('Module\%s\Api\Forms', ucfirst($action[0]));

            // Check class exists
            if (class_exists($class)) {

                // Call api
                Pi::api('forms', $action[0])->postReview($params);
            }
        }
    }
}
