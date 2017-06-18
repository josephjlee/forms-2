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
namespace Module\Forms\Validator;

use Pi;
use Zend\Validator\AbstractValidator;

class ElementValue extends AbstractValidator
{
    const TAKEN = 'elementValue';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::TAKEN => 'This value can to be empty',
    );

    /**
     * Slug validate
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        if (in_array($context['type'], array('checkbox', 'radio', 'select')) && empty($value)) {
            $this->error(static::TAKEN);
            return false;
        }
        return true;
    }
}