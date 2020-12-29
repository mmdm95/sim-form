<?php

namespace Sim\Form\FormElements;

class Radio extends Input
{
    /**
     * @var string $type
     */
    protected $type = 'radio';

    /**
     * Radio constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name, $this->type);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type): Radio
    {
        $this->attributes['type'] = $this->type;
        return $this;
    }

    /**
     * Do nothing
     *
     * {@inheritdoc}
     */
    public function inputIsCsrf(bool $answer): Radio
    {
        return $this;
    }

    /**
     * Do nothing
     *
     * {@inheritdoc}
     */
    public function inputIsCaptcha(bool $answer): Radio
    {
        return $this;
    }
}