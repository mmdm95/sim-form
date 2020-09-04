<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class UniqueArrayValidation extends AbstractValidation
{
    protected $error_message = 'Value is not a unique list.';

    /**
     * Please specify [array value] to validate
     *
     * @see https://stackoverflow.com/questions/3145607/php-check-if-an-array-has-duplicates - in comment section of question
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 2 || !is_array($_[0])) {
            return false;
        }

        [$value] = $_;
        return count($value) == count(array_unique($value));
    }
}