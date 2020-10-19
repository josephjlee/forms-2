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

use Pi;
use Laminas\InputFilter\InputFilter;

class ManageFilter extends InputFilter
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

        // slug
        $this->add(
            [
                'name'       => 'slug',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Forms\Validator\SlugDuplicate(
                        [
                            'module' => Pi::service('module')->current(),
                            'table'  => 'form',
                            'id'     => $option['id'],
                        ]
                    ),
                ],
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

        // register_need
        $this->add(
            [
                'name'     => 'register_need',
                'required' => false,
            ]
        );

        // review_need
        $this->add(
            [
                'name'     => 'review_need',
                'required' => false,
            ]
        );

        // review_action
        $this->add(
            [
                'name'     => 'review_action',
                'required' => false,
            ]
        );

        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => false,
            ]
        );

        // time_start
        $this->add(
            [
                'name'     => 'time_start',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // time_end
        $this->add(
            [
                'name'     => 'time_end',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // main_image
        $this->add(
            [
                'name'     => 'main_image',
                'required' => false,
            ]
        );
    }
}
