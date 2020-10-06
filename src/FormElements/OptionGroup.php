<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class OptionGroup extends AbstractFieldComposite
{
    /**
     * OptionGroup constructor.
     * @param string $label_name
     */
    public function __construct(string $label_name = '')
    {
        parent::__construct('optgroup', null);
        $this->setAttribute('label', $label_name);
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