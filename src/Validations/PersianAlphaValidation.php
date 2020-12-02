<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class PersianAlphaValidation extends AbstractValidation
{
    protected $error_message = 'String is not valid persian alpha .';

    /**
     * Please specify [numeric value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1 || is_numeric($_[0])) {
            return false;
        }

        [$value] = $_;
        return (bool)preg_match('/^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u', $value);
    }
}