<?php

namespace Sim\Form\Abstracts;

use Sim\Form\FormElements\Option;
use Sim\Form\FormElements\OptionGroup;
use Sim\Form\FormElements\SimpleText;

abstract class AbstractSelectElement extends AbstractFieldComposite
{
    /**
     * @var array $options
     */
    protected $options = [];

    /**
     * @param string|int $key
     * @return mixed|null
     */
    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param $value
     * @return static
     */
    public function addOption($value)
    {
        if ($value instanceof Option || $value instanceof OptionGroup) {
            $this->options[] = $value;
        } elseif (is_string($value)) {
            $this->createOption($value);
        }
        return $this;
    }

    /**
     * @param array $values
     * @return static
     */
    public function addOptions(array $values)
    {
        $this->createOptions($values);
        return $this;
    }

    /**
     * @param array $values
     */
    protected function createOptions(array $values)
    {
        foreach ($values as $k => $value) {
            if ($value instanceof Option || $value instanceof OptionGroup) {
                $this->options[] = $value;
            } elseif (is_string($value) || is_array($value)) {
                $this->createOption($value, is_string($k) ? $k : null);
            }
        }
    }

    /**
     * @param string|null $key
     * @param array|string $value
     */
    protected function createOption($value, ?string $key = null)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $txt = $v;
                $val = $k;
                break;
            }
        } else {
            $val = $value;
            $txt = $value;
        }
        if (!isset($txt) || !isset($val)) return;

        if (!is_null($key)) {
            if (!isset($this->options[$key])) {
                $this->options[$key] = [];
            }
            $this->options[$key][] = (new Option($val))->add(new SimpleText($txt));
        } else {
            $this->options[] = (new Option($val))->add(new SimpleText($txt));
        }
    }
}