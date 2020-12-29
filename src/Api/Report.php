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
 * Pi::api('report', 'forms')->chart($formId);
 */

class Report extends AbstractApi
{
    public function chart($formId)
    {
        // Get list of questions
        $questionList = Pi::api('form', 'forms')->getView($formId);
        foreach ($questionList as $key => $value) {
            if (!in_array($value['type'], ['radio']) && $value['status'] != 1 && empty($value['value'])) {
                unset($questionList[$key]);
            } else {

                // Make columns
                $columns = [];

                // Make element array from string
                $i           = 1;
                $elementList = [];
                $elements    = explode('|', $questionList[$key]['value']);
                foreach ($elements as $element) {
                    $elementKey                        = sprintf('element%s', $i++);
                    $elementList['label'][$elementKey] = $element;

                    // Set count column for select
                    $columns[$elementKey] = new Expression(sprintf("count(CASE `value` WHEN '%s' THEN `value` END)", $element));
                }

                // Make query and get count of answers
                $where     = ['form' => $formId, 'element' => $value['id']];
                $select    = Pi::model('data', 'forms')->select()->columns($columns)->where($where)->limit(1);
                $dateCount = Pi::model('data', 'forms')->selectWith($select)->current();
                if ($dateCount) {
                    $dateCount = $dateCount->toArray();
                    foreach ($dateCount as $dateKey => $dateValue) {
                        $elementList['data'][$dateKey] = $dateValue;
                    }
                }

                // Set count and list to result
                $questionList[$key]['value'] = $elementList;
            }
        }

        // result full information
        return $questionList;
    }
}
