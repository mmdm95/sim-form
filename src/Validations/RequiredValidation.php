<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class RequiredValidation extends AbstractValidation
{
    protected $error_message = 'Value is required.';

    /**
     * Please specify [array|scalar value] to validate
     *
     * Returns true if value is not empty array or string
     * otherwise returns false
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1 || (!is_scalar($_[0]) && !is_array($_[0]))) {
            return false;
        }

        [$value] = $_;
        return is_array($value) ? !empty($value) : ('' !== trim((string)$value));
    }
}