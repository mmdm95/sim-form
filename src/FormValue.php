<?php

namespace Sim\Form;

class FormValue
{
    /**
     * @var array $all_field_values
     */
    protected $all_field_values = [];

    /**
     * @var mixed|null $value
     */
    protected $value = null;

    /**
     * @var string $name
     */
    protected $name = '';

    /**
     * @var string $alias
     */
    protected $alias = '';

    /**
     * FormValue constructor.
     * @param array $field_values
     */
    public function __construct(array $field_values)
    {
        $this->all_field_values = $field_values;
    }

    /**
     * @param $value
     * @return FormValue
     */
    public function setValue($value): FormValue
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $name
     * @return FormValue
     */
    public function setName(string $name): FormValue
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $alias
     * @return FormValue
     */
    public function setAlias(string $alias): FormValue
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param $value
     * @return FormValue
     */
    public function replaceValue($value): FormValue
    {
        $this->all_field_values = $this->_set_param($this->all_field_values, explode('.', $this->getName()), $value);
        return $this;
    }

    /**
     * @return array
     */
    public function getReplacedValues(): array
    {
        return $this->all_field_values;
    }

    /**
     * Set value to array
     *
     * @param $data
     * @param array $names
     * @param $value
     * @return mixed|null
     */
    private function _set_param($data, array $names, $value)
    {
        // if data isn't an array or object
        if (is_scalar($data)) {
            return null;
        }

        // if there is no $names parameter, return
        if (!count($names)) {
            return null;
        }

        // get first name to fetch
        $first = array_shift($names);

        if ('*' == $first) {
            foreach ($data as $k => &$v) {
                $res = $this->_set_param($v, $names, $value);
                if (!is_null($res)) {
                    $v = $res;
                }
            }
            return $data;
        } elseif (is_null($first) || !isset($data[$first])) {
            return null;
        } elseif (count($names) == 0) {
            $data[$first] = $value;
            return $data;
        } else {
            return $this->_set_param($data[$first], $names, $value);
        }
    }
}