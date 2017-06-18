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

class LinkForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new LinkFilter($this->option);
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
        // elements
        if (isset($this->option['elements']) && !empty($this->option['elements'])) {
            foreach ($this->option['elements'] as $element) {
                $this->add(array(
                    'name' => sprintf('element-%s', $element['id']),
                    'type' => 'checkbox',
                    'options' => array(
                        'label' => $element['title'],
                    ),
                    'attributes' => array(
                        'description' => sprintf(__('Form type is : %s'), $element['type']),
                        'value' => $element['link'],
                        'required' => false,
                    )
                ));
            }
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Link to form'),
            )
        ));
    }
}