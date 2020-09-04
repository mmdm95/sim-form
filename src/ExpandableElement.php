<?php

namespace Sim\Form;

use Sim\Form\Abstracts\AbstractFieldComposite;
use Sim\Form\Exceptions\ElementException;

class ExpandableElement extends AbstractFieldComposite
{
    /**
     * @return string
     * @throws ElementException
     */
    public function render(): string
    {
        if (is_null($this->getTagName())) {
            throw new ElementException("Element's tag is not specified!");
        }
        $output = parent::render();
        $output = "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
        if ($this->has_error) {
            $output .= $this->errorElement()->render();
        }
        return $output;
    }
}