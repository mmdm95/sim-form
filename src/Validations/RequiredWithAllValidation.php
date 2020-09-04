<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class RequiredWithAllValidation extends AbstractValidation
{
    protected $error_message = 'Value is required.';

    /**
     * Please specify [array|scalar value] and at least [another value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || (!is_scalar($_[0]) && !is_array($_[0]))) {
            return false;
        }

        $value = array_shift($_);

        $counter = 0;
        foreach ($_ as $v) {
            if (is_array($v) ? !empty($v) : ('' !== trim((string)$v))) {
                $counter++;
            }
        }

        if ((count($_) == $counter) && (is_array($value) ? !empty($value) : ('' !== trim((string)$value)))) {
            return true;
        }
        return false;
    }
}