<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class UrlValidation extends AbstractValidation
{
    protected $error_message = 'Url is invalid.';

    /**
     * Please specify [string value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_string($_[0])) {
            return false;
        }

        [$value] = $_;
        return (false !== filter_var($value, \FILTER_VALIDATE_URL));
    }
}