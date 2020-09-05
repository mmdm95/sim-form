<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class HexColorValidation extends AbstractValidation
{
    protected $error_message = 'Hex value is invalid.';

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
        return (bool)preg_match("/#?([a-f0-9]{3}){1,2}\b/i", $value);
    }
}