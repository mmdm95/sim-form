<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class TimestampValidation extends AbstractValidation
{
    protected $error_message = 'Timestamp is invalid.';

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
        if (is_string($value)) {
            return ((string)(int)$value === $value)
                && ($value <= \PHP_INT_MAX)
                && ($value >= ~\PHP_INT_MAX);
        } elseif (is_numeric($value)) {
            $value = (int)$value;
            return ($value <= \PHP_INT_MAX)
                && ($value >= ~\PHP_INT_MAX);
        }
        return false;
    }
}