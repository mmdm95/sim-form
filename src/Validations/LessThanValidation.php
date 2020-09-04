<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class LessThanValidation extends AbstractValidation
{
    protected $error_message = 'Value is not less than max.';

    /**
     * Please specify [numeric value] and [numeric max] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_numeric($_[0]) || !is_numeric($_[1])) {
            return false;
        }

        [$value, $max] = $_;
        return ($value + 0) < ($max + 0);
    }
}