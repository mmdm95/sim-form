<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Label extends AbstractFieldComposite
{
    /**
     * Label constructor.
     */
    public function __construct()
    {
        parent::__construct('label');
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