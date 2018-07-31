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

use Pi\Form\Form as BaseForm;

class ViewForm extends BaseForm
{
    public function __construct($name = null, $option = [])
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
        $this->add([
            'name'       => 'id',
            'attributes' => [
                'type' => 'hidden',
            ],
        ]);
        if (isset($this->option['elements']) && !empty($this->option['elements'])) {
            foreach ($this->option['elements'] as $element) {
                switch ($element['type']) {
                    case 'text':
                    case 'email':
                    case 'phone':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'options'    => [
                                'label' => $element['title'],
                            ],
                            'attributes' => [
                                'type'        => 'text',
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'number':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'options'    => [
                                'label' => $element['title'],
                            ],
                            'attributes' => [
                                'type'        => 'number',
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'textarea':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'options'    => [
                                'label' => $element['title'],
                            ],
                            'attributes' => [
                                'type'        => 'textarea',
                                'rows'        => '5',
                                'cols'        => '40',
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'checkbox':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'type'       => 'multi_checkbox',
                            'options'    => [
                                'label'         => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ],
                            'attributes' => [
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'radio':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'type'       => 'radio',
                            'options'    => [
                                'label'         => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ],
                            'attributes' => [
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'select':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'type'       => 'select',
                            'options'    => [
                                'label'         => $element['title'],
                                'value_options' => $this->makeArray($element['value']),
                            ],
                            'attributes' => [
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'percent':
                        // Set percent
                        $percent = [];
                        for ($i = 1; $i <= 100; $i++) {
                            $percent[$i] = $i;
                        }
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'type'       => 'select',
                            'options'    => [
                                'label'         => $element['title'],
                                'value_options' => $percent,
                            ],
                            'attributes' => [
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;

                    case 'star':
                        $this->add([
                            'name'       => sprintf('element-%s', $element['id']),
                            'type'       => 'radio',
                            'options'    => [
                                'label'         => $element['title'],
                                'value_options' => [
                                    1 => 1,
                                    2 => 2,
                                    3 => 3,
                                    4 => 4,
                                    5 => 5,
                                ],
                            ],
                            'attributes' => [
                                'description' => $element['description'],
                                'required'    => $element['required'] ? true : false,
                            ],
                        ]);
                        break;
                }
            }
        }
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
            ],
        ]);
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