<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class GreaterThanEqualLengthValidation extends AbstractValidation
{
    protected $error_message = 'Value is not grater than or equal length to min.';

    /**
     * Please specify [scalar value] and [numeric min] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_scalar($_[0]) || !is_numeric($_[1])) {
            return false;
        }

        [$value, $min] = $_;
        return mb_strlen((string)$value) >= (int)$min;
    }
}