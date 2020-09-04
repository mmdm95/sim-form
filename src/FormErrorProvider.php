<?php

namespace Sim\Form;

use Sim\Form\Abstracts\AbstractFormErrorProvider;

class FormErrorProvider extends AbstractFormErrorProvider
{
    /**
     * FormErrorProvider constructor.
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
    }
}