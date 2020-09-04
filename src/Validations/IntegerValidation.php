<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class IntegerValidation extends AbstractValidation
{
    protected $error_message = 'Number is invalid.';

    /**
     * Please specify [value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1) {
            return false;
        }

        [$value] = $_;
        return (false !== filter_var($value, \FILTER_VALIDATE_INT));
    }
}