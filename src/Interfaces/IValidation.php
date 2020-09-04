<?php

namespace Sim\Form\Interfaces;

interface IValidation
{
    /**
     * @param string $message
     * @return static
     */
    public function setError(string $message);

    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @param mixed ...$_
     * @return bool
     */
    public function validate(...$_): bool;
}