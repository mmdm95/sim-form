<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class AlphaValidation extends AbstractValidation
{
    protected $error_message = 'You should just use alpha.';

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
        return preg_match('/^([a-z])+$/i', (string)$value);
    }
}