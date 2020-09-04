<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class FloatValidation extends AbstractValidation
{
    protected $error_message = 'Float number is invalid.';

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

        return is_float($value);
    }
}