<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class AlphaNumericValidation extends AbstractValidation
{
    protected $error_message = 'You should just use alpha-numeric.';

    /**
     * Please specify [scalar value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_scalar($_[0])) {
            return false;
        }

        [$value] = $_;
        return (bool)preg_match('/^([a-z0-9])+$/i', (string)$value);
    }
}