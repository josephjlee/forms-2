<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Forms\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class ElementForm extends BaseForm
{
    public function __construct($name = null, $option = array())
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
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'label' => __('Type'),
                'value_options' => array(
                    'text' => __('Text'),
                    'email' => __('email'),
                    'phone' => __('phone'),
                    'textarea' => __('textarea'),
                    'checkbox' => __('Checkbox'),
                    'radio' => __('Radio button'),
                    'select' => __('Select box'),
                ),
            ),
            'attributes' => array(
                  'required' => true,
            )
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ),
            ),
        ));
        // order
        /* $this->add(array(
            'name' => 'order',
            'options' => array(
                'label' => __('View order'),
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
            )
        )); */
        // value
        $this->add(array(
            'name' => 'value',
            'options' => array(
                'label' => __('General value'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                'description' => __('Use `|` as delimiter to separate select box / Checkbox / Radio button elements'),
            )
        ));
        // description
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => __('Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
            )
        ));
        // required
        $this->add(array(
            'name' => 'required',
            'type' => 'checkbox',
            'options' => array(
                'label' => __('Required'),
            ),
            'attributes' => array(
                'required' => false,
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}