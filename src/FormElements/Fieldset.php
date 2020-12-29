<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Fieldset extends AbstractFieldComposite
{
    /**
     * Fieldset constructor.
     */
    public function __construct()
    {
        parent::__construct('fieldset');
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