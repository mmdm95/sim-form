<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractSelectElement;

class OptionGroup extends AbstractSelectElement
{
    /**
     * OptionGroup constructor.
     * @param string $label_name
     * @param array $options
     */
    public function __construct(string $label_name = '', array $options = [])
    {
        parent::__construct('optgroup', null);
        $this->setAttribute('label', $label_name);
        $this->createOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value = '')
    {
        foreach ($this->options as $key => $option) {
            if ($option instanceof Option) {
                $option->setValue($value);
            } elseif (is_string($key) && is_array($option)) {
                /**
                 * @var array $option
                 * @var Option $opt
                 */
                foreach ($option as $opt) {
                    if ($opt instanceof Option) {
                        $opt->setValue($value);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $output = parent::render();
        foreach ($this->options as $key => $option) {
            if ($option instanceof Option) {
                /**
                 * @var Option $option
                 */
                $output .= $option->render();
            }
        }
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }
}