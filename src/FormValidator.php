<?php

namespace Sim\Form;

use Sim\Form\Abstracts\AbstractFormValidator;
use Sim\Form\Exceptions\FormException;
use Sim\Form\Utils\LocalUtil;
use Sim\Form\Utils\ValidatorUtil;
use Sim\Form\Validations\PasswordValidation;

class FormValidator extends AbstractFormValidator
{
    /**
     * @var array
     */
    protected $all_fields_values = [];

    /**
     * @var array
     */
    protected $fields_values_bag = [];

    /**
     * @var array
     */
    protected $fields_alias = [];

    /**
     * @var array
     */
    protected $optional_fields = [];

    /**
     * @var bool
     */
    protected $stop_execution_at_first_error = false;

    /**
     * @var bool
     */
    protected $stop_execution_at_first_error_each_group = false;

    /**
     * @var bool
     */
    protected $to_persian = false;

    /**
     * @var bool
     */
    protected $to_arabic = false;

    /**
     * @var bool
     */
    protected $to_english = false;

    /**
     * @var bool
     */
    protected $replace_value_to_local = false;

    /**
     * @var array
     */
    protected $to_persian_fields = [];

    /**
     * @var array
     */
    protected $to_arabic_fields = [];

    /**
     * @var array
     */
    protected $to_english_fields = [];

    /**
     * @var bool
     */
    protected $replace_fields_value_to_local = false;

    /**
     * @var array
     */
    protected $to_persian_except_fields = [];

    /**
     * @var array
     */
    protected $to_arabic_except_fields = [];

    /**
     * @var array
     */
    protected $to_english_except_fields = [];

    /**
     * FormValidator constructor.
     * @param array $on
     */
    public function __construct(array $on = [])
    {
        parent::__construct();

        if (empty($on)) {
            $on = $_POST + $_FILES;
        }
        $this->all_fields_values = $on;
        $this->fields_values_bag = $on;
    }

    /**
     * @param array $on
     * @return static
     */
    public function setAllValues(array $on)
    {
        if (!empty($on)) {
            $this->all_fields_values = $on;
            $this->fields_values_bag = $on;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAllValues(): array
    {
        return $this->all_fields_values;
    }

    /**
     * @param array $on
     * @return static
     */
    public function appendAllValues(array $on)
    {
        if (!empty($on)) {
            $this->all_fields_values = array_replace_recursive($this->all_fields_values, $on);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getBagValues(): array
    {
        return $this->fields_values_bag;
    }

    /**
     * @return static
     */
    public function resetBagValues()
    {
        $this->fields_values_bag = [];
        return $this;
    }

    /**
     * Set default value of specific key, if it's not set
     *
     * Note:
     *   Can pass array of key, value pairs through first argument
     *   or a key as first argument and value as second one.
     *
     * Note:
     *   Instead of nested array, you can pass an array with dot
     *   like:
     *     k1.k2 => v1
     *   which means:
     *   [
     *     k1 => [
     *       k2 => v1,
     *     ]
     *   ]
     *
     * @param $key
     * @param $value
     * @return static
     */
    public function setDefaultValue($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                if (is_string($k) && !is_null($v)) {
                    if (!ValidatorUtil::hasInArray($this->all_fields_values, $k, false)) {
                        ValidatorUtil::setToArray($this->all_fields_values, $k, $v);
                    }
                }
            }
        } elseif (is_string($key) && !is_null($value)) {
            if (!ValidatorUtil::hasInArray($this->all_fields_values, $key, false)) {
                ValidatorUtil::setToArray($this->all_fields_values, $key, $value);
            }
        }

        return $this;
    }

    /**
     * Return value of $key
     *
     * If the value is null, returns $prefer instead
     *
     * @param string $key
     * @param mixed|null $prefer
     * @param bool $from_bag
     * @param bool $normalize
     * @return array|string|null
     */
    public function getFieldValue(string $key, $prefer = null, bool $from_bag = false, bool $normalize = true)
    {
        if ($normalize) {
            $key = ValidatorUtil::normalizeFieldKey($key);
        }

        return $this->_get_filed_value($key, $from_bag)[0] ?? $prefer;
    }

    /**
     * Check if value of a specific key is empty or not
     *
     * @param string $key
     * @return bool
     */
    public function isFieldValueEmpty(string $key): bool
    {
        $value = $this->getFieldValue($key);
        return $this->_is_empty($value);
    }

    /**
     * A key, value pair to map field's name to its alias
     *
     * [
     *   name => 'Name of user',
     *   ...
     * ]
     *
     * @param array $alias
     * @return static
     */
    public function setFieldsAlias(array $alias)
    {
        $this->fields_alias = $alias;
        return $this;
    }

    /**
     * Return all fields aliases
     *
     * @return array
     */
    public function getFieldsAlias(): array
    {
        return $this->fields_alias;
    }

    /**
     * Get specific field's alias if exists(include empty string) or return prefer value
     *
     * @param string $key
     * @param string $prefer
     * @return string
     */
    public function getFieldAlias(string $key, string $prefer = ''): string
    {
        try {
            return isset($this->fields_alias[$key])
                ? strval($this->fields_alias[$key])
                : $prefer;
        } catch (\Exception $e) {
            return $prefer;
        }
    }

    /**
     * @param array $fields
     * @return static
     */
    public function setOptionalFields(array $fields)
    {
        $this->optional_fields = $fields;
        return $this;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function stopValidationAfterFirstError(bool $answer)
    {
        $this->stop_execution_at_first_error = $answer;
        return $this;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function stopValidationAfterFirstErrorOnEachFieldGroup(bool $answer)
    {
        $this->stop_execution_at_first_error_each_group = $answer;
        return $this;
    }

    /**
     * @param bool $answer
     * @param bool $replace
     * @return static
     */
    public function toPersianValue(bool $answer, bool $replace = false)
    {
        $this->to_persian = $answer;
        $this->replace_value_to_local = $replace;
        return $this;
    }

    /**
     * @param bool $answer
     * @param bool $replace
     * @return static
     */
    public function toArabicValue(bool $answer, bool $replace = false)
    {
        $this->to_arabic = $answer;
        $this->replace_value_to_local = $replace;
        return $this;
    }

    /**
     * @param bool $answer
     * @param bool $replace
     * @return static
     */
    public function toEnglishValue(bool $answer, bool $replace = false)
    {
        $this->to_english = $answer;
        $this->replace_value_to_local = $replace;
        return $this;
    }

    /**
     * @param array $fields
     * @param bool $replace
     * @return static
     */
    public function toPersianValueFields(array $fields, bool $replace = false)
    {
        $this->to_persian_fields = $fields;
        $this->replace_fields_value_to_local = $replace;
        return $this;
    }

    /**
     * @param array $fields
     * @param bool $replace
     * @return static
     */
    public function toArabicValueFields(array $fields, bool $replace = false)
    {
        $this->to_arabic_fields = $fields;
        $this->replace_fields_value_to_local = $replace;
        return $this;
    }

    /**
     * @param array $fields
     * @param bool $replace
     * @return static
     */
    public function toEnglishValueFields(array $fields, bool $replace = false)
    {
        $this->to_english_fields = $fields;
        $this->replace_fields_value_to_local = $replace;
        return $this;
    }

    /**
     * @param array $fields
     * @return static
     */
    public function toPersianValueExceptFields(array $fields)
    {
        $this->to_persian_except_fields = $fields;
        return $this;
    }

    /**
     * @param array $fields
     * @return static
     */
    public function toArabicValueExceptFields(array $fields)
    {
        $this->to_arabic_except_fields = $fields;
        return $this;
    }

    /**
     * @param array $fields
     * @return static
     */
    public function toEnglishValueExceptFields(array $fields)
    {
        $this->to_english_except_fields = $fields;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setInput(string $name, string $default = '')
    {
        $values = $this->getFieldValue($name, null, true);
        if (is_null($values) || (is_array($values) && empty($values))) {
            return $default;
        }
        return is_array($values) ? ValidatorUtil::getNRemoveFromArray($this->fields_values_bag, $name) : $values;
    }

    /**
     * {@inheritdoc}
     */
    public function setCheckbox(string $name, string $value = '', bool $default = false)
    {
        return $this->_set_input($name, $value, $default, ' checked="checked" ');
    }

    /**
     * {@inheritdoc}
     */
    public function setRadio(string $name, string $value = '', string $default = '')
    {
        return $this->_set_input($name, $value, $default, ' checked="checked" ');
    }

    /**
     * {@inheritdoc}
     */
    public function setSelect(string $name, string $value = '', bool $default = false)
    {
        return $this->_set_input($name, $value, $default, ' selected="selected" ');
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function alphaNum(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_alpha_num($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function alpha(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_alpha($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function email(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_email($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function equalLength($length, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($length) {
            return $this->_equal_length($value, $length);
        }, $callback, [
            '{length}' => $length,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function equal($compareTo, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($compareTo) {
            return $this->_equal($value, $compareTo);
        }, $callback, [
            '{compareTo}' => $compareTo,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function isFloat(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_float($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function greaterThanEqualLength($min, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min) {
            return $this->_greater_than_equal_length($value, $min);
        }, $callback, [
            '{min}' => $min,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function greaterThanEqual($min, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min) {
            return $this->_greater_than_equal($value, $min);
        }, $callback, [
            '{min}' => $min,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function greaterThanLength($min, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min) {
            return $this->_greater_than_length($value, $min);
        }, $callback, [
            '{min}' => $min,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function greaterThan($min, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min) {
            return $this->_greater_than($value, $min);
        }, $callback, [
            '{min}' => $min,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function hexColor(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_hex_color($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function isIn(array $list, ?string $message = null, ?callable $callback = null, bool $strict = false)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($list, $strict) {
            return $this->_is_in($value, $list, $strict);
        }, $callback, [
            'list' => '[' . implode(',', $list) . ']',
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function isInteger(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_integer($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function ipv4(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_ipv4($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function ipv6(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_ipv6($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function ip(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_ip($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function isChecked(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_checked($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function lengthBetween($min, $max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min, $max) {
            return $this->_is_length_between($value, $min, $max);
        }, $callback, [
            '{min}' => $min,
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function lessThanEqualLength($max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($max) {
            return $this->_less_than_equal_length($value, $max);
        }, $callback, [
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function lessThanEqual($max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($max) {
            return $this->_less_than_equal($value, $max);
        }, $callback, [
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function lessThanLength($max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($max) {
            return $this->_less_than_length($value, $max);
        }, $callback, [
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function lessThan($max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($max) {
            return $this->_less_than($value, $max);
        }, $callback, [
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function between($min, $max, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($min, $max) {
            return $this->_between($value, $min, $max);
        }, $callback, [
            '{min}' => $min,
            '{max}' => $max,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function password($strength = PasswordValidation::STRENGTH_NORMAL, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($strength) {
            return $this->_password($value, $strength);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function regex($regex, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($regex) {
            return $this->_regex($value, $regex);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function required(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_required($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function requiredWithAll($names, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($names) {
            $namesValue = [];
            if (is_array($names)) {
                foreach ($names as $name) {
                    if (is_string($name)) {
                        $namesValue[] = $this->getFieldValue($name, null);
                    }
                }
            } elseif (is_string($names)) {
                $namesValue[] = $this->getFieldValue($names, null);
            }
            return $this->_required_with_all($value, $namesValue);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function requiredWith($names, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($names) {
            $namesValue = [];
            if (is_array($names)) {
                foreach ($names as $name) {
                    if (is_string($name)) {
                        $namesValue[] = $this->getFieldValue($name, null);
                    }
                }
            } elseif (is_string($names)) {
                $namesValue[] = $this->getFieldValue($names, null);
            }
            return $this->_required_with($value, $namesValue);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function timestamp(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_timestamp($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function isUnique(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_is_unique($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function url(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_url($value);
        }, $callback);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function match($fstName, $sndName, ?string $message = null, callable $callback = null)
    {
        $allSndNames = is_array($sndName) ? $sndName : (is_string($sndName) ? [$sndName] : []);
        //-----
        if (is_array($fstName)) {
            if (!empty($fstName)) {
                $firstAlias = array_keys($fstName)[0];
                $fstName = array_shift($fstName);
                if (is_numeric($firstAlias)) $firstAlias = $fstName;
                $first = $this->getFieldValue($fstName, null);
            } else {
                $firstAlias = '';
                $first = null;
            }
        } else {
            $first = !is_null($fstName) ? $this->getFieldValue($fstName, null) : null;
            $firstAlias = $fstName;
        }
        //-----
        if (!is_null($first)) {
            $isFstArray = is_array($first);
            $isFstString = is_string($first);

            foreach ($allSndNames as $alias => $theSndName) {
                $snd = is_array($theSndName) && !empty($theSndName)
                    ? array_shift($theSndName)
                    : (is_string($theSndName) ? $theSndName : null);

                $sndAlias = !is_numeric($alias) ? $alias : $snd;
                $second = !is_null($snd) ? $this->getFieldValue($snd, null) : null;
                $result = false;
                //-----
                if (!is_null($theSndName)) {
                    if (!is_null($second)) {
                        $isSndArray = is_array($second);
                        $isSndString = is_string($second);
                        if ($isFstArray && $isSndArray) {
                            if (count($first) == count($second)) {
                                foreach ($first as $k => $value) {
                                    if (!isset($second[$k]) || $value != $second[$k]) {
                                        break;
                                    }
                                }
                                $result = true;
                            }
                        } elseif ($isFstString && $isSndString) {
                            $result = $first == $second;
                        }
                    }
                    //-----
                    if (!$result && !is_null($sndAlias)) {
                        $message = $this->_get_default_message($message, __FUNCTION__);

                        $message = $this->_replace_error_placeholders($message, [
                            '{first}' => $firstAlias,
                            '{second}' => $sndAlias,
                        ]);

                        $this->setError($theSndName, $message);
                        if (is_callable($callback)) {
                            call_user_func_array($callback, [$fstName, $theSndName]);
                        }
                    }
                    $this->_set_status($result);
                }
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function fileDuplicate(string $file, ?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) use ($file) {
            $f = !empty($file) && is_string($file) ? $file . $value : $value;
            return is_file($f) && file_exists($f);
        }, $callback, [
            '{filename}' => $file
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws FormException
     */
    public function custom($callback, $message)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value, $field, $alias) use ($message, $callback) {
            $res = false;
            if (is_callable($callback)) {
                $formValue = new FormValue($this->all_fields_values);
                $formValue->setValue($value)
                    ->setName($field)
                    ->setAlias($alias);

                $res = call_user_func_array($callback, [$formValue]);

                // set replaced fields to current fields
                $this->all_fields_values = $formValue->getReplacedValues();
            }
            return $res;
        });
        return $this;
    }

    /**
     * Set input fields like select, radio, checkbox, etc.
     *
     * @param string $name
     * @param string $value
     * @param bool $default
     * @param string $return
     * @return string
     */
    protected function _set_input($name, $value, $default, $return): string
    {
        $values = $this->getFieldValue($name, null, true);
        $value = (string)$value;
        if (empty($_POST) || empty($value) || empty($values)) {
            return (bool)$default ? $return : '';
        }
        if (is_array($values)) {
            if (in_array($value, $values)) {
                return $return;
            }
        }
        return $values == $value ? $return : '';
    }

    /**
     * Execute callback for array or string name variable
     *
     * @param array $name
     * @param string|null $message
     * @param string $method_name
     * @param callable $callback
     * @param callable|null $userCallback
     * @param array $extra_placeholders
     * @return bool
     */
    protected function _execute($name, $message, $method_name, $callback, callable $userCallback = null, array $extra_placeholders = []): bool
    {
        if ($this->stop_execution_at_first_error) {
            if (!$this->getStatus()) return false;
        }

        $res = false;
        if (is_array($name)) {
            foreach ($name as $alias => $n) {
                // get alias from alias collection
                if (!is_string($alias)) {
                    $alias = $this->fields_alias[$n] ?? $alias;
                }

                $res = $this->_execute_atomic($alias, $n, $message, $method_name, $callback, $userCallback, $extra_placeholders);
                if (is_null($res)) {
                    $res = false;
                }

                if ($this->stop_execution_at_first_error_each_group && !$res) {
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * Atomic function for execute to reduce code duplication
     *
     * @param string|null $alias
     * @param string $name
     * @param string|null $message
     * @param string $method_name
     * @param callable $callback
     * @param callable|null $userCallback
     * @param array $extra_placeholders
     * @return bool|null
     */
    private function _execute_atomic($alias, $name, $message, $method_name, $callback, callable $userCallback = null, array $extra_placeholders = []): ?bool
    {
        $res = false;
        $alias = is_string($alias) ? $alias : $name;
        if (is_callable($callback)) {
            $errorValue = '';

            // valitron approach
//            list($values, $multiple) = $this->getPart($this->all_fields_values, explode('.', $name), false);

            // this library approach
            [$values] = $this->_get_filed_value($name);
            if (is_null($values)) $values = null;
            if (!is_array($values)) $values = [$values];

            foreach ($values as $value) {
                $v = $value;

                if (
                    ($this->to_arabic || in_array($name, $this->to_arabic_fields)) &&
                    !in_array($name, $this->to_arabic_except_fields)
                ) {
                    $v = LocalUtil::toArabic($v);
                }
                if (
                    ($this->to_persian || in_array($name, $this->to_persian_fields)) &&
                    !in_array($name, $this->to_persian_except_fields)
                ) {
                    $v = LocalUtil::toPersian($v);
                }
                if (
                    ($this->to_english || in_array($name, $this->to_english_fields)) &&
                    !in_array($name, $this->to_english_except_fields)
                ) {
                    $v = LocalUtil::toEnglish($v);
                }
                if (
                    ($this->replace_value_to_local || $this->replace_fields_value_to_local) &&
                    !in_array($name, $this->to_arabic_except_fields) &&
                    !in_array($name, $this->to_persian_except_fields) &&
                    !in_array($name, $this->to_english_except_fields)
                ) {
                    $n = ValidatorUtil::setParameters($this->all_fields_values, explode('.', $name), $v);
                    if (!empty($n)) {
                        $this->all_fields_values = $n;
                        $this->fields_values_bag = $n;
                    }
                }

                if (null != $userCallback && is_callable($userCallback)) {

                    $formValue = new FormValue($this->all_fields_values);
                    $formValue->setValue($v)
                        ->setName($name)
                        ->setAlias($alias);

                    call_user_func_array($userCallback, [$formValue]);

                    // set replaced fields to current fields
                    $this->all_fields_values = $formValue->getReplacedValues();
                    $this->fields_values_bag = $this->all_fields_values;
                }

                if (in_array($name, $this->optional_fields) && $this->_is_empty($v)) {
                    $res = true;
                    continue;
                }

                $res = call_user_func_array($callback, [$v, $name, $alias]);
                $errorValue = $value;
                if (!$res) break;
            }

            // add error message to error collection if even there
            // is a single invalidation through validation process
            if (!$res) {
                $message = $this->_get_default_message($message, $method_name);

                // replace placeholders to actual values
                $message = $this->_replace_error_placeholders($message, array_merge([
                    '{alias}' => $alias,
                    '{value}' => $errorValue,
                    '{name}' => $name,
                    '{type}' => gettype($errorValue),
                ], $extra_placeholders));

                $this->setError($name, $message);
            }

            // store result according to previous results
            $this->_set_status($res);
        }

        return $res;
    }

    /**
     * This code directly come from valitron validation library,
     * but there is a simpler version in our code and there is no
     * need these codes anymore if every thing is OK!
     *
     * @see https://github.com/vlucas/valitron/blob/9268adeeb48ba155e35dca861f5990283e14eafb/src/Valitron/Validator.php#L1127
     * @param $data
     * @param $identifiers
     * @param bool $allow_empty
     * @return array
     */
    private function getPart($data, $identifiers, $allow_empty = false)
    {
        // Catches the case where the field is an array of discrete values
        if (is_array($identifiers) && count($identifiers) === 0) {
            return array($data, false);
        }
        // Catches the case where the data isn't an array or object
        if (is_scalar($data)) {
            return array(null, false);
        }
        $identifier = array_shift($identifiers);
        // Glob match
        if ($identifier === '*') {
            $values = array();
            foreach ($data as $row) {
                list($value, $multiple) = $this->getPart($row, $identifiers, $allow_empty);
                if ($multiple) {
                    $values = array_merge($values, $value);
                } else {
                    $values[] = $value;
                }
            }
            return array($values, true);
        } // Dead end, abort
        elseif ($identifier === null || !isset($data[$identifier])) {
            if ($allow_empty) {
                //when empty values are allowed, we only care if the key exists
                return array(null, array_key_exists($identifier, $data));
            }
            return array(null, false);
        } // Match array element
        elseif (count($identifiers) === 0) {
            if ($allow_empty) {
                //when empty values are allowed, we only care if the key exists
                return array(null, array_key_exists($identifier, $data));
            }
            return array($data[$identifier], $allow_empty);
        } // We need to go deeper
        else {
            return $this->getPart($data[$identifier], $identifiers, $allow_empty);
        }
    }

    /**
     * Get value of field with detection!
     * detection: post or file field
     *
     * @param $name
     * @param bool $from_bag
     * @return array|string|null
     */
    private function _get_filed_value($name, bool $from_bag = false)
    {
        if (is_string($name)) {
            $arr = $from_bag ? $this->fields_values_bag : $this->all_fields_values;
            return ValidatorUtil::getParameters($arr, explode('.', $name));
        }
        return null;
    }

    /**
     * [
     *   {alias} => 'alias',
     *   ...
     * ]
     *
     * @param string $message
     * @param array $placeholders
     * @return mixed|string
     */
    private function _replace_error_placeholders(string $message, array $placeholders)
    {
        foreach ($placeholders as $placeholder => $value) {
            if (is_scalar($value)) {
                $message = str_replace($placeholder, $value, $message);
            }
        }
        return $message;
    }

    /**
     * @param $message
     * @param $method_name
     * @return string
     */
    private function _get_default_message($message, $method_name)
    {
        if (is_null($message) && $this->language_type != 'en') {
            $this->_load_language_settings();
            $message = $this->language_settings[$method_name] ?? '';
        }
        return (string)$message;
    }
}