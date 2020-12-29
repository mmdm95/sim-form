<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractSelectElement;

class Select extends AbstractSelectElement
{
    /**
     * Select constructor.
     * @param string|null $name
     * @param array $options
     */
    public function __construct(?string $name = null, array $options = [])
    {
        parent::__construct('select', $name);
        $this->createOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value = '')
    {
        foreach ($this->options as $key => $option) {
            if ($option instanceof OptionGroup || $option instanceof Option) {
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
            if ($option instanceof OptionGroup) {
                $output .= $option->render();
            } elseif ($option instanceof Option) {
                /**
                 * @var Option $option
                 */
                $output .= $option->render();
            } elseif (is_string($key) && is_array($option)) {
                $optGroup = new OptionGroup($key);

                /**
                 * @var array $option
                 * @var Option $opt
                 */
                foreach ($option as $opt) {
                    if ($opt instanceof Option) {
                        $optGroup->add($opt);
                    }
                }
                $output .= $optGroup->render();
            }
        }
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }
}