<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Button extends AbstractFieldComposite
{
    /**
     * @var string $type
     */
    protected $type = 'button';

    /**
     * Button constructor.
     * @param string|null $name
     * @param string|null $type
     */
    public function __construct(?string $name = null, ?string $type = null)
    {
        parent::__construct('button', $name);
        $this->setType($type);
    }

    /**
     * Set element's type
     *
     * @param string $type
     * @return Button
     */
    public function setType($type): Button
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
     * @return string
     */
    public function render(): string
    {
        $output = parent::render();
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }
}