<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFormElement;

class Input extends AbstractFormElement
{
    /**
     * @var string $type
     */
    protected $type = 'text';

    /**
     * @var Label|null $label
     */
    protected $label = null;

    /**
     * Input constructor.
     * @param string|null $name
     * @param string|null $type
     */
    public function __construct(string $name = null, string $type = null)
    {
        parent::__construct('input', $name);
        $this->setType($type);
    }

    /**
     * Set element's type
     *
     * @param string $type
     * @return Input
     */
    public function setType($type): Input
    {
        if (!is_null($type)) {
            $this->attributes['type'] = $type;
        } else {
            $this->attributes['type'] = $this->type;
        }
        return $this;
    }

    /**
     * Get element's type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->getFromAttribute('type');
    }

    /**
     * @param bool $answer
     * @return Input
     */
    public function inputIsCsrf(bool $answer): Input
    {
        $this->is_csrf_token = $answer;
        return $this;
    }

    /**
     * @param bool $answer
     * @return Input
     */
    public function inputIsCaptcha(bool $answer): Input
    {
        $this->is_captcha = $answer;
        return $this;
    }

    /**
     * @return Label|null
     */
    public function label(): Label
    {
        if (!($this->label instanceof Label)) {
            $this->label = new Label();
        }
        return $this->label;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $content = '';
        if (!is_null($this->label) && $this->label instanceof Label) {
            $content .= $this->label->render();
        }
        $content .= "<{$this->getTagName()} {$this->attributesToString()}>\n";
        return $content;
    }
}