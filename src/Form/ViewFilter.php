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

use Zend\InputFilter\InputFilter;

class ViewFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // elements
        if (isset($option['elements']) && !empty($option['elements'])) {
            foreach ($option['elements'] as $element) {
                switch ($element['type']) {
                    case 'text':
                    case 'email':
                    case 'phone':
                    case 'number':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                                'filters'  => [
                                    [
                                        'name' => 'StringTrim',
                                    ],
                                ],
                            ]
                        );
                        break;

                    case 'textarea':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                                'filters'  => [
                                    [
                                        'name' => 'StringTrim',
                                    ],
                                ],
                            ]
                        );
                        break;

                    case 'checkbox':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                            ]
                        );
                        break;

                    case 'radio':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                            ]
                        );
                        break;

                    case 'select':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                            ]
                        );
                        break;

                    case 'percent':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                            ]
                        );
                        break;

                    case 'star':
                        $this->add(
                            [
                                'name'     => sprintf('element-%s', $element['id']),
                                'required' => $element['required'] ? true : false,
                            ]
                        );
                        break;
                }
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