<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class EmailValidation extends AbstractValidation
{
    protected $error_message = 'Email is invalid.';

    /**
     * Please specify [scalar value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_string($_[0])) {
            return false;
        }

        [$value] = $_;
        return (false !== filter_var($value, \FILTER_VALIDATE_EMAIL));
    }
}