<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Div extends AbstractFieldComposite
{
    /**
     * Div constructor.
     */
    public function __construct()
    {
        parent::__construct('div');
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