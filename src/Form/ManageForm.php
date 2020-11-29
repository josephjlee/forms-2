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

use Pi\Form\Form as BaseForm;

class ManageForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ManageFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // slug
        $this->add(
            [
                'name'       => 'slug',
                'options'    => [
                    'label' => __('Slug'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Used as form URL value : must be unique, short, and user oriented'),
                ],
            ]
        );

        // description
        $this->add(
            [
                'name'       => 'description',
                'options'    => [
                    'label'  => __('Description'),
                    'editor' => 'html',
                ],
                'attributes' => [
                    'type' => 'editor',
                ],
            ]
        );

        // register_need
        $this->add(
            [
                'name'       => 'register_need',
                'options'    => [
                    'label'         => __('Need register'),
                    'value_options' => [
                        1 => __('Yes'),
                        0 => __('No'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'       => 1,
                    'description' => __('User need register to system and login for fell this form'),
                ],
            ]
        );

        // review_need
        $this->add(
            [
                'name'       => 'review_need',
                'options'    => [
                    'label'         => __('Need review'),
                    'value_options' => [
                        1 => __('Yes'),
                        0 => __('No'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'       => 1,
                    'description' => __('All form records should be review by admin'),
                ],
            ]
        );

        // show_answer
        $this->add(
            [
                'name'       => 'show_answer',
                'options'    => [
                    'label'         => __('Show answer'),
                    'value_options' => [
                        1 => __('Yes'),
                        0 => __('No'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'       => 1,
                    'description' => __('Show answer after submit form by user'),
                ],
            ]
        );

        // multi_steps
        $this->add(
            [
                'name'       => 'multi_steps',
                'options'    => [
                    'label'         => __('Multi steps'),
                    'value_options' => [
                        1 => __('Yes'),
                        0 => __('No'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'       => 0,
                    'description' => __('Multi steps form view and show each step after submit before step'),
                ],
            ]
        );

        // review_action
        $this->add(
            [
                'name'       => 'review_action',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Review Action'),
                    'value_options' => $this->makeArray($this->option['review_action']),
                ],
                'attributes' => [
                    'description' => __('Do action after accept form record on admin review'),
                    'required'    => true,
                ],
            ]
        );

        // status
        $this->add(
            [
                'name'       => 'status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // time_start
        $this->add(
            [
                'name'       => 'time_start',
                'type'       => 'datepicker',
                'options'    => [
                    'label'      => __('Time start'),
                    'datepicker' => [
                        'format'         => 'yyyy/mm/dd',
                        'autoclose'      => true,
                        'todayBtn'       => true,
                        'todayHighlight' => true,
                        'weekStart'      => 1,
                        'zIndexOffset'   => 10000,

                    ],
                ],
                'attributes' => [
                    'required' => true,
                    'value'    => date('Y-m-d'),
                    'class'    => 'event-time-start',
                ],
            ]
        );

        // time_end
        $this->add(
            [
                'name'       => 'time_end',
                'type'       => 'datepicker',
                'options'    => [
                    'label'      => __('Time end'),
                    'datepicker' => [
                        'format'         => 'yyyy/mm/dd',
                        'autoclose'      => true,
                        'todayBtn'       => true,
                        'todayHighlight' => true,
                        'weekStart'      => 1,
                        'zIndexOffset'   => 10000,
                    ],
                ],
                'attributes' => [
                    'required' => true,
                    'value'    => date('Y-m-d', strtotime('+6 months')),
                    'class'    => 'event-time-end',
                ],
            ]
        );

        // main_image
        $this->add(
            [
                'name'    => 'main_image',
                'type'    => 'Module\Media\Form\Element\Media',
                'options' => [
                    'label'  => __('Main image'),
                    'module' => 'forms',
                ],
            ]
        );

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }

    public function makeArray($string)
    {
        $list     = [];
        $variable = explode('|', $string);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}
