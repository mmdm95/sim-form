<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class NumberBetweenValidation extends AbstractValidation
{
    protected $error_message = 'Number is not between the other two numbers.';

    /**
     * Please specify [scalar value] and [numeric min] and [numeric max] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 3 || !is_scalar($_[0]) || !is_numeric($_[1]) || !is_numeric($_[2])) {
            return false;
        }

        [$value, $min, $max] = $_;
        $value = is_string($value) && !is_numeric($value) ? (int)$value : ($value + 0);
        return $value >= ($min + 0) && $value <= ($max + 0);
    }
}