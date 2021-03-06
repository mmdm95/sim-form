<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class LessThanLengthValidation extends AbstractValidation
{
    protected $error_message = 'Value is not less length than max.';

    /**
     * Please specify [scalar value] and [numeric max] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_scalar($_[0]) || !is_numeric($_[1])) {
            return false;
        }

        [$value, $max] = $_;
        return mb_strlen((string)$value) < (int)$max;
    }
}