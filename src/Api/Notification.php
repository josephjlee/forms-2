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

/*
 * Pi::api('notification', 'forms')->put($params);
 */

class Notification extends AbstractApi
{
    public function put($params)
    {
        // Set to admin
        $toAdmin   = [
            Pi::config('adminmail') => Pi::config('adminname'),
        ];

        // Send
        Pi::service('notification')->send($toAdmin, 'put', $params, $this->getModule());
    }
}