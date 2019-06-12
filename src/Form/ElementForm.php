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

class ElementForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ElementFilter($this->option);
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

        // type
        $this->add(
            [
                'name'       => 'type',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Type'),
                    'value_options' => [
                        'text'     => __('Text'),
                        'number'   => __('Number'),
                        'textarea' => __('Text Area'),
                        'checkbox' => __('Check Box'),
                        'radio'    => __('Radio Button'),
                        'select'   => __('Select Box'),
                        'star'     => __('Star'),
                        'percent'  => __('Percent'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // status
        $this->add(
            [
                'name'    => 'status',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
            ]
        );

        // value
        $this->add(
            [
                'name'       => 'value',
                'options'    => [
                    'label' => __('General value'),
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'rows'        => '5',
                    'cols'        => '40',
                    'description' => __('Use `|` as delimiter to separate select box / Checkbox / Radio button elements'),
                ],
            ]
        );

        // description
        $this->add(
            [
                'name'       => 'description',
                'options'    => [
                    'label' => __('Description'),
                ],
                'attributes' => [
                    'type' => 'textarea',
                    'rows' => '5',
                    'cols' => '40',
                ],
            ]
        );

        // required
        $this->add(
            [
                'name'       => 'required',
                'type'       => 'checkbox',
                'options'    => [
                    'label' => __('Required'),
                ],
                'attributes' => [
                    'required' => false,
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
                ],
            ]
        );
    }
}