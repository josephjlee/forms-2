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
        print_r($this->option);
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
                            )
                        ));
                        break;

                    case 'textarea':

                        break;

                    case 'checkbox':

                        break;

                    case 'radio':

                        break;

                    case 'select':

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
}