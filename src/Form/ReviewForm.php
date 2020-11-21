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

class ReviewForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ReviewForm($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // review_status
        $this->add(
            [
                'name'       => 'review_status',
                'options'    => [
                    'label'         => __('Review Status'),
                    'value_options' => [
                        0 => __('Pending'),
                        1 => __('Accepted'),
                        2 => __('Rejected'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value' => 1,
                ],
            ]
        );

        // review_result
        $this->add(
            [
                'name'       => 'review_result',
                'options'    => [
                    'label' => __('Review Result'),
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'rows'        => '5',
                    'cols'        => '40',
                    'description' => '',
                    'required'    => false,
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
}
