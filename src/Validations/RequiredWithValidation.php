<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class RequiredWithValidation extends AbstractValidation
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

        $res = false;
        foreach ($_ as $v) {
            if(is_array($v) ? !empty($v) : ('' !== trim((string)$v))) {
                $res = true;
                break;
            }
        }

        if($res) {
            if(is_array($value) ? !empty($value) : ('' !== trim((string)$value))) {
                return true;
            }
            return false;
        }
        return true;
    }
}