<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Label extends AbstractFieldComposite
{
    /**
     * @return string
     */
    public function render(): string
    {
        $this->setTagName('label');
        $output = parent::render();
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }
}