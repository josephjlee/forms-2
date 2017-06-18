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
use Zend\InputFilter\InputFilter;

class LinkFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // elements
        if (isset($option['elements']) && !empty($option['elements'])) {
            foreach ($option['elements'] as $element) {
                // important
                $this->add(array(
                    'name' => sprintf('element-%s', $element['id']),
                    'required' => false,
                ));
            }
        }
    }
}