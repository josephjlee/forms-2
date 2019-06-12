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

class ElementFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // id
        $this->add(
            [
                'name'     => 'id',
                'required' => false,
            ]
        );
        // title
        $this->add(
            [
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => true,
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => false,
            ]
        );
        // order
        /* $this->add(array(
            'name' => 'order',
            'required' => false,
        )); */
        // value
        $this->add(
            [
                'name'     => 'value',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                /* 'validators' => array(
                    new \Module\Forms\Validator\ElementValue,
                ), */
            ]
        );
        // description
        $this->add(
            [
                'name'     => 'description',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // required
        $this->add(
            [
                'name'     => 'required',
                'required' => false,
            ]
        );
    }
}