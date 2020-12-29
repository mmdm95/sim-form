<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Span extends AbstractFieldComposite
{
    /**
     * Span constructor.
     */
    public function __construct()
    {
        parent::__construct('span');
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