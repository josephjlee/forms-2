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

class ViewForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ViewFilter($this->option);
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
        if (isset($this->option['elements']) && !empty($this->option['elements'])) {
            foreach ($this->option['elements'] as $element) {
                switch ($element['type']) {
                    case 'text':
                    case 'email':
                    case 'phone':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'options' => array(
                                'label' => $element['title'],
                            ),
                            'attributes' => array(
                                'type' => 'text',
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'number':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'options' => array(
                                'label' => $element['title'],
                            ),
                            'attributes' => array(
                                'type' => 'number',
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'textarea':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'options' => array(
                                'label' => $element['title'],
                            ),
                            'attributes' => array(
                                'type' => 'textarea',
                                'rows' => '5',
                                'cols' => '40',
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'checkbox':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'type' => 'multi_checkbox',
                            'options' => array(
                                'label' => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ),
                            'attributes' => array(
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'radio':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'type' => 'radio',
                            'options' => array(
                                'label' => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ),
                            'attributes' => array(
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'select':
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'type' => 'select',
                            'options' => array(
                                'label' => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ),
                            'attributes' => array(
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;

                    case 'percent':
                        // Set percent
                        $percent = array();
                        for ($i = 1; $i <= 100; $i++) {
                            $percent[$i] = $i;
                        }
                        $this->add(array(
                            'name' => sprintf('element-%s', $element['id']),
                            'type' => 'select',
                            'options' => array(
                                'label' => $element['title'],
                                'value_options' => $percent,
                            ),
                            'attributes' => array(
                                'description' => $element['description'],
                                'required' => $element['required'] ? true : false,
                            )
                        ));
                        break;
                }
            }
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }

    public function makeArray($values)
    {
        $list = array();
        $variable = explode('|', $values);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}