<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class PersianMobileValidation extends AbstractValidation
{
    protected $error_message = 'Mobile is invalid.';

    /**
     * Please specify [numeric value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_numeric($_[0])) {
            return false;
        }

        [$value] = $_;
        return (bool)preg_match("/^(098|\+98|0)?9\d{9}$/", $value);
    }
}