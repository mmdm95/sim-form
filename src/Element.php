<?php

namespace Sim\Form;

use Sim\Form\Abstracts\AbstractFormElement;
use Sim\Form\Exceptions\ElementException;

class Element extends AbstractFormElement
{
    /**
     * @return mixed|string
     * @throws ElementException
     */
    public function render(): string
    {
        if (is_null($this->getTagName())) {
            throw new ElementException("Element's tag is not specified!");
        }
        $output = "<{$this->getTagName()} {$this->attributesToString()}>\n";
        if ($this->has_error) {
            $output .= $this->errorElement()->render();
        }
        return $output;
    }
}