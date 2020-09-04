<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class IsCheckedValidation extends AbstractValidation
{
    protected $error_message = 'Checkbox is not checked.';

    /**
     * Please specify [value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1) {
            return false;
        }

        $checkedArr = ['yes', 'on', 1, '1', true];
        [$value] = $_;
        return in_array($value, $checkedArr);
    }
}