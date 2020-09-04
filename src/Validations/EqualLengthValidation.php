<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class EqualLengthValidation extends AbstractValidation
{
    protected $error_message = 'Value is not equal to length.';

    /**
     * Please specify [scalar value] and [int length] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_scalar($_[0])) {
            return false;
        }

        [$value, $length] = $_;
        return mb_strlen((string)$value) == (int)$length;
    }
}