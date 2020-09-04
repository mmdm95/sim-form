<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class EqualValidation extends AbstractValidation
{
    protected $error_message = 'Value is not equal to second value.';

    /**
     * Please specify [scalar value] and [scalar second_value] to validate
     *
     * @see https://www.php.net/manual/en/function.is-numeric.php#107326 - convert numeric to int or float
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_scalar($_[0]) || !is_scalar($_[1])) {
            return false;
        }

        [$value, $secondValue] = $_;
        if (is_numeric($value)) {
            $value = $value + 0;
            $secondValue = is_numeric($secondValue) ? $secondValue + 0 : 0;
            return $value == $secondValue;
        } else if (is_string($value)) {
            return (string)$value == (string)$secondValue;
        }
        return false;
    }
}