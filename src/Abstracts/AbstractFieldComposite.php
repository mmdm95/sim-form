<?php

namespace Sim\Form\Abstracts;

use Sim\Form\Interfaces\IFormElement;

abstract class AbstractFieldComposite extends AbstractFormElement
{
    /**
     * @var array $fields
     */
    protected $components = [];

    /**
     * @param IFormElement $component
     * @return AbstractFieldComposite
     */
    public function add(IFormElement $component): AbstractFieldComposite
    {
        $name = $component->getIdentifier();
        $this->components[$name] = $component;
        $this->components[$name]->setParent($this);
        return $this;
    }

    /**
     * @param IFormElement $component
     * @return AbstractFieldComposite
     */
    public function remove(IFormElement &$component): AbstractFieldComposite
    {
        $this->components = array_filter($this->components, function ($child) use (&$component) {
            if($component->getParent() === $this) {
                $component->setParent(null);
            }
            return $child != $component;
        });
        return $this;
    }

    /**
     * @return array
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Set a value to an attribute
     *
     * @param string $identifier
     * @param string $attribute
     * @return void
     */
    public function setComponentsAttribute(string $identifier, string $attribute): void
    {
        $args = func_get_args();
        $value = $args[2] ?? '';
        $value = !is_string($value) ? '' : $value;

        /**
         * @var IFormElement $component
         */
        foreach ($this->components as $componentName => $component) {
            if ($componentName == $identifier) {
                $component->setAttribute(mb_strtolower($attribute), $value);
            }
        }
    }

    /**
     * Get value of an attribute
     *
     * @param string $identifier
     * @return string
     */
    public function getComponentsAttribute(string $identifier): string
    {
        $args = func_get_args();
        $attribute = $args[1] ?? null;
        return $this->getFromAttributeComponents($identifier, $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function setComponentsAttributes(array $attributes): void
    {
        /**
         * @var IFormElement $component
         */
        foreach ($this->components as $name => $component) {
            if (isset($attributes[$name])) {
                $component->setAttributes($attributes[$name]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getComponentsAttributes(): array
    {
        $args = func_get_args();
        $wantedIdentifier = $args[0] ?? null;

        $attributes = [];

        /**
         * @var IFormElement $component
         */
        foreach ($this->components as $identifier => $component) {
            if (!is_null($wantedIdentifier)) {
                if ($identifier == $wantedIdentifier) {
                    $attributes = $component->getAttributes();
                    break;
                }
            } else {
                $attributes[$identifier] = $component->getAttributes();
            }
        }

        return $attributes;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $output = "";

        /**
         * @var IFormElement $component
         */
        foreach ($this->components as $name => $component) {
            $output .= $component->render();
        }
        return $output;
    }

    /**
     * Return attributes in string format
     *
     * @return string
     */
    protected function attributesToStringComponents(): string
    {
        $args = func_get_args();
        $identifier = $args[0] ?? null;

        $attributes = $this->getComponentsAttributes();
        $newAttr = '';

        if (!is_null($identifier) && isset($attributes[$identifier])) {
            foreach ($attributes[$identifier] as $attr => $value) {
                $newAttr .= "{$attr}=\"{$value}\" ";
            }
        } else {
            foreach ($this->attributes as $attr => $value) {
                $newAttr .= "{$attr}=\"{$value}\" ";
            }
        }
        $newAttr = trim($newAttr);

        return $newAttr;
    }

    /**
     * Get any value from attributes with name of attribute
     *
     * @param $identifier
     * @return mixed|string
     */
    protected function getFromAttributeComponents($identifier): string
    {
        $args = func_get_args();
        $attributeName = $args[1] ?? null;

        if (!is_string($attributeName) || empty($attributeName)) return '';

        $attributes = $this->getComponentsAttributes($identifier);
        $value = $attributes[$attributeName] ?? '';
        $value = trim($value);

        return $value;
    }
}