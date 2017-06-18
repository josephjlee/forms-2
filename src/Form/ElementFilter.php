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

class ElementFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'required' => true,
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => false,
        ));
        // order
        /* $this->add(array(
            'name' => 'order',
            'required' => false,
        )); */
        // value
        $this->add(array(
            'name' => 'value',
            'required' => false,
            'validators' => array(
                new \Module\Forms\Validator\ElementValue,
            ),
        ));
    }
}