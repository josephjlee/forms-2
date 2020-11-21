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

namespace Module\Forms\Form;

use Laminas\InputFilter\InputFilter;

class ViewFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // elements
        if (isset($option['elements']) && !empty($option['elements'])) {
            foreach ($option['elements'] as $element) {
                switch ($element['type']) {
                    default:
                    case 'text':
                    case 'email':
                    case 'url':
                    case 'tel':
                    case 'number':
                    case 'textarea':
                        $filters = [
                            [
                                'name' => 'StringTrim',
                            ],
                        ];
                        break;

                    case 'checkbox':
                    case 'radio':
                    case 'select':
                        $filters = [];
                        break;
                }

                $formFilter = [
                    'name'     => sprintf('element-%s', $element['id']),
                    'required' => $element['required'] ? true : false,
                    'filters'  => $filters,
                ];

                $this->add($formFilter);
            }
        }
    }

    public function makeArray($values)
    {
        $list     = [];
        $variable = explode('|', $values);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}