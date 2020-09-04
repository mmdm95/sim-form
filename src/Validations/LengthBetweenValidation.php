<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class LengthBetweenValidation extends AbstractValidation
{
    protected $error_message = 'Value\'s length is not between the other two numbers.';

    /**
     * Please specify [scalar value] and [numeric min] and [numeric max] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 3 || (!is_scalar($_[0])) || !is_numeric($_[1]) || !is_numeric($_[2])) {
            return false;
        }

        [$value, $min, $max] = $_;
        $length = mb_strlen((string)$value);
        return $length >= (int)$min && $length <= (int)$max;
    }
}