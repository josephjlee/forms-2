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
        if (isset($this->option['elements']) && !empty($this->option['elements'])) {
            foreach ($this->option['elements'] as $element) {
                switch ($element['type']) {
                    case 'text':
                    case 'email':
                    case 'url':
                    case 'tel':
                    case 'number':
                        $attributes = [
                            'type' => $element['type'],
                        ];
                        $options = [];
                        break;

                    case 'textarea':
                        $attributes = [
                            'type' => $element['type'],
                            'rows' => '5',
                            'cols' => '40',
                        ];
                        $options = [];
                        break;

                    case 'checkbox':
                        $attributes = [];
                        $options = [
                            'value_options' => $this->makeArray($element['value']),
                        ];

                        $elementType = 'multi_checkbox';
                        break;

                    case 'radio':
                        $attributes = [];
                        $options = [
                            'value_options'    => $this->makeArray($element['value']),
                            'label_attributes' => [
                                'class' => 'form-check',
                            ],
                        ];

                        $elementType = $element['type'];
                        break;

                    case 'select':
                        $attributes = [];
                        $options = [
                            'value_options' => $this->makeArray($element['value']),
                        ];

                        $elementType = $element['type'];
                        break;
                }

                $formElement = [
                    'name'       => sprintf('element-%s', $element['id']),
                    'options'    => [
                        'label' => $element['title'],
                    ],
                    'attributes' => [
                        'description' => $element['description'],
                        'required'    => $element['required'] ? true : false,
                    ],
                ];

                if (isset($elementType) && !empty($elementType)) {
                    $formElement['type'] = $elementType;
                }
                if (isset($options) && !empty($options) && is_array($options)) {
                    foreach ($options as $key => $value) {
                        if (!isset($formElement['options'][$key])) {
                            $formElement['options'][$key] = $value;
                        }
                    }
                }
                if (isset($attributes) && !empty($attributes) && is_array($attributes)) {
                    foreach ($attributes as $key => $value) {
                        if (!isset($formElement['attributes'][$key])) {
                            $formElement['attributes'][$key] = $value;
                        }
                    }
                }

                $this->add($formElement);
            }
        }

        // captcha
        if (!Pi::service('authentication')->hasIdentity()) {
            $captchaMode = 2;
            if ($captchaElement = Pi::service('form')->getReCaptcha($captchaMode)) {
                $this->add($captchaElement);
            }
        }

        // security
        $this->add(
            [
                'name' => 'security',
                'type' => 'csrf',
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
