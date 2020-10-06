<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFormElement;

class SimpleText extends AbstractFormElement
{
    /**
     * @var string $text
     */
    protected $text = '';

    /**
     * SimpleText constructor.
     * @param string $text
     */
    public function __construct(string $text = '')
    {
        parent::__construct(null, null);
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function render(): string
    {
        return $this->text;
    }
}