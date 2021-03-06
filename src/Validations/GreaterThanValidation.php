<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class GreaterThanValidation extends AbstractValidation
{
    protected $error_message = 'Value is not greater than min.';

    /**
     * Please specify [numeric value] and [numeric min] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_numeric($_[0]) || !is_numeric($_[1])) {
            return false;
        }

        [$value, $min] = $_;
        return ($value + 0) > ($min + 0);
    }
}