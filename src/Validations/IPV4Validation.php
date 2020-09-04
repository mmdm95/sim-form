<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class IPV4Validation extends AbstractValidation
{
    protected $error_message = 'IP-v4 is invalid.';

    /**
     * Please specify [string value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1 || !is_string($_[0])) {
            return false;
        }

        [$value] = $_;
        return (false !== filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4));
    }
}