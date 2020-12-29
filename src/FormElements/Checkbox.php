<?php

namespace Sim\Form\FormElements;

class Checkbox extends Input
{
    /**
     * @var string $type
     */
    protected $type = 'checkbox';

    /**
     * Checkbox constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name, $this->type);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type): Checkbox
    {
        $this->attributes['type'] = $this->type;
        return $this;
    }

    /**
     * Do nothing
     *
     * {@inheritdoc}
     */
    public function inputIsCsrf(bool $answer): Checkbox
    {
        return $this;
    }

    /**
     * Do nothing
     *
     * {@inheritdoc}
     */
    public function inputIsCaptcha(bool $answer): Checkbox
    {
        return $this;
    }
}