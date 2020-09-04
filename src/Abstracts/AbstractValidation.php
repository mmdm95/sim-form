<?php

namespace Sim\Form\Abstracts;

use Sim\Form\Interfaces\IValidation;

abstract class AbstractValidation implements IValidation
{
    protected $error_message = '';

    /**
     * {@inheritdoc}
     */
    public function setError(string $message)
    {
        $this->error_message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): string
    {
        return $this->error_message;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function validate(...$_): bool;
}