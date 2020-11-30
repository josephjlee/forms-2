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

class AnswerForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new AnswerFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        $htmlTemplate
            = <<<'EOT'
<div class="media">
    <div class="media-body">
        <h5 class="mt-0">%s</h5>
        <p>%s</p>
    </div>
</div>
EOT;

        // Make list of answers
        if (isset($this->option['listAnswer']) && !empty($this->option['listAnswer']) && is_array($this->option['listAnswer'])) {
            foreach ($this->option['listAnswer'] as $singleAnswer) {

                // Make html description
                $html = sprintf($htmlTemplate, $singleAnswer['question_title'], $singleAnswer['answer_date']);

                // Set property list
                $this->add(
                    [
                        'name'       => $singleAnswer['question_id'],
                        'type'       => 'description',
                        'options'    => [
                            'label' => '',
                        ],
                        'attributes' => [
                            'description' => $html,
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
                    'value' => (isset($this->option['submit_btn']) && !empty($this->option['submit_btn'])) ? $this->option['submit_btn'] : __('Submit'),
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }
}
