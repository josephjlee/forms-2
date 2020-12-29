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

use Laminas\InputFilter\InputFilter;

class LinkFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // elements
        if (isset($option['elements']) && !empty($option['elements'])) {
            foreach ($option['elements'] as $element) {
                // important
                $this->add(
                    [
                        'name'     => sprintf('element-%s', $element['id']),
                        'required' => false,
                    ]
                );
            }
        }
    }
}
