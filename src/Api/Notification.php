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
        $toAdmin = [
            Pi::config('adminmail') => Pi::config('adminname'),
        ];

        // Send to admin
        Pi::service('notification')->send($toAdmin, 'receive-admin', $params, $this->getModule());

        // Send to user
        if (isset($params['user_name']) && !empty($params['user_name']) && isset($params['user_email']) && !empty($params['user_email'])) {

            // Set to user
            $toUser = [
                $params['user_email'] => $params['user_name'],
            ];

            // Send to user
            Pi::service('notification')->send($toUser, 'receive-user', $params, $this->getModule());
        }
    }
}
