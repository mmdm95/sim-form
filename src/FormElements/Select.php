<?php

namespace Sim\Form\FormElements;

use Sim\Form\Abstracts\AbstractFieldComposite;

class Select extends AbstractFieldComposite
{
    /**
     * @var array $options
     */
    protected $options = [];

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
    public function setValue(?string $value = '')
    {
        if (!empty($this->getName())) {
            $values = $_POST[$this->getName()] ?? null;
            foreach ($this->options as $key => $option) {
                if ($option instanceof Option) {
                    $value = $option->getAttribute('value');

                    /**
                     * @var Option $option
                     */
                    if (!empty($value) && in_array($value, $values)) {
                        $option->setAttribute('checked', 'checked');
                    }
                } elseif (is_string($key) && is_array($option)) {
                    /**
                     * @var array $option
                     * @var Option $opt
                     */
                    foreach ($option as $opt) {
                        if ($opt instanceof Option) {
                            $value = $opt->getAttribute('value');
                            if (!empty($value) && in_array($value, $values)) {
                                $opt->setAttribute('checked', 'checked');
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param string $value
     * @return static
     */
    public function addOption(string $value)
    {
        $this->createOption($value);
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

    /**
     * @param array $values
     */
    protected function createOptions(array $values)
    {
        foreach ($values as $k => $value) {
            $this->createOption($value, is_string($k) ? $k : null);
        }
    }

    /**
     * @param string|null $key
     * @param string $value
     */
    protected function createOption(string $value, ?string $key = null)
    {
        if (!is_null($key)) {
            if (!isset($this->options[$key])) {
                $this->options[$key] = [];
            }
            $this->options[$key][] = new Option($value);
        } else {
            $this->options[] = new Option($value);
        }
    }
}