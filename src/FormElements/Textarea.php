<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFormElement;

class Textarea extends AbstractFormElement
{
    /**
     * @var string
     */
    protected $text = '';

    /**
     * Textarea constructor.
     * @param string $name
     * @param SimpleText|string|int|float|bool|null $text - array, object, resource etc. are not acceptable
     */
    public function __construct(string $name = null, $text = null)
    {
        $this->setText($text);
        parent::__construct('textarea', $name);
    }

    /**
     * @param $text
     * @return Textarea
     */
    public function setText($text): Textarea
    {
        if ($text instanceof SimpleText) {
            $this->text = $text->render();
        } elseif (is_scalar($text)) {
            $this->text = (string)$text;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value = '')
    {
        if (is_null($value)) $value = '';
        $this->setText($value);
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $text = $this->getText();
        $content = "<{$this->getTagName()} {$this->attributesToString()}>\n$text</{$this->getTagName()}>\n";
        return $content;
    }
}