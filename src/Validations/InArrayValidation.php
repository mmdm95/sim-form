<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class InArrayValidation extends AbstractValidation
{
    protected $error_message = 'Value is not in list.';

    /**
     * Please specify [array|scalar value] and [array list] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || (!is_scalar($_[0]) && !is_array($_[0])) || !is_array($_[1])) {
            return false;
        }

        [$value, $list] = $_;

        if(is_array($value)) {
            $newValue = array_intersect($value, $list);
            sort($newValue);
            sort($value);
            return $newValue == $value;
        } else {
            $strict = $_[2] ?? false;
            return in_array((string)$value, $list, (bool)$strict);
        }
    }
}