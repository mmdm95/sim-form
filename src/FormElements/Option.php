<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Option extends AbstractFieldComposite
{
    /**
     * Option constructor.
     * @param string $value
     */
    public function __construct(string $value = '')
    {
        parent::__construct('option', null);
        $this->setAttribute('value', $value);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $output = parent::render();
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }
}