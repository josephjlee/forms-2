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

class ElementFilter extends InputFilter
{
    public function __construct($option = [])
    {
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

        // answer
        $this->add(
            [
                'name'     => 'answer',
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

        // is_name
        $this->add(
            [
                'name'     => 'is_name',
                'required' => false,
            ]
        );

        // is_email
        $this->add(
            [
                'name'     => 'is_email',
                'required' => false,
            ]
        );

        // is_mobile
        $this->add(
            [
                'name'     => 'is_mobile',
                'required' => false,
            ]
        );
    }
}
