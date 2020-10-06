<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Wrapper extends AbstractFieldComposite
{
    public function __construct(?string $tag_name = null)
    {
        parent::__construct(!empty($tag_name) ? $tag_name : 'div', null);
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