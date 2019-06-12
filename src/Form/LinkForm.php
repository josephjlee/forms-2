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

class LinkForm extends BaseForm
{
    public function __construct($name = null, $option = [])
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
        $this->add(
            [
                'name'       => 'id',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );
        // elements
        if (isset($this->option['elements']) && !empty($this->option['elements'])) {
            foreach ($this->option['elements'] as $element) {
                $this->add(
                    [
                        'name'       => sprintf('element-%s', $element['id']),
                        'type'       => 'checkbox',
                        'options'    => [
                            'label' => $element['title'],
                        ],
                        'attributes' => [
                            'description' => sprintf(__('Form type is : %s'), $element['type']),
                            'value'       => $element['link'],
                            'required'    => false,
                        ],
                    ]
                );
            }
        }
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Link to form'),
                ],
            ]
        );
    }
}