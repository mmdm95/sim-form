<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class RegexValidation extends AbstractValidation
{
    protected $error_message = 'Regex is invalid.';

    /**
     * Please specify [scalar regex] and [scalar value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 2 || !is_scalar($_[0]) || !is_scalar($_[1])) {
            return false;
        }

        [$value, $regex] = $_;
        return (bool)preg_match((string)$regex, (string)$value);
    }
}