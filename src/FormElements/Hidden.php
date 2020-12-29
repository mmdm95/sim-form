<?php

namespace Sim\Form\FormElements;

class Hidden extends Input
{
    /**
     * @var string $type
     */
    protected $type = 'hidden';

    /**
     * Hidden constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name, $this->type);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type): Hidden
    {
        $this->attributes['type'] = $this->type;
        return $this;
    }

    /**
     * Do nothing
     *
     * {@inheritdoc}
     */
    public function inputIsCaptcha(bool $answer): Hidden
    {
        return $this;
    }
}