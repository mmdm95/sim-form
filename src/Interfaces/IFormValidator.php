<?php

namespace Sim\Form\Interfaces;

interface IFormValidator extends IFormError
{
    /**
     * Set value of a normal input (not radio and checkbox)
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function setInput(string $name, string $default = '');

    /**
     * Set checkbox input value
     *
     * @param string $name
     * @param string $value
     * @param bool $default
     * @return mixed
     */
    public function setCheckbox(string $name, string $value = '', bool $default = false);

    /**
     * Set radio input value
     *
     * @param string $name
     * @param string $value
     * @param string $default
     * @return mixed
     */
    public function setRadio(string $name, string $value = '', string $default = '');

    /**
     * Set select input values
     *
     * @param string $name
     * @param string $value
     * @param bool $default
     * @return mixed
     */
    public function setSelect(string $name, string $value = '', bool $default = false);

    /**
     * Check for alpha numeric string
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function alphaNum(?string $message = null, callable $callback = null);

    /**
     * Check for alpha string
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function alpha(?string $message = null, callable $callback = null);

    /**
     * Check if an email is valid or not
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function email(?string $message = null, callable $callback = null);

    /**
     * validate specific filed value to be equal length to $length
     *
     * Note: It use just for string values
     *
     * @param $length
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function equalLength($length, ?string $message = null, callable $callback = null);

    /**
     * validate specific name to be equal to a value
     *
     * Note: It use for both string length and numeric values
     *
     * @param $compareTo
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function equal($compareTo, ?string $message = null, callable $callback = null);

    /**
     * Check if a value is float
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function isFloat(?string $message = null, callable $callback = null);

    /**
     * validate specific value's length to be greater than or equal to a value
     *
     * @param $min
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function greaterThanEqualLength($min, ?string $message = null, callable $callback = null);

    /**
     * validate specific name to be greater than or equal to a value
     *
     * @param $min
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function greaterThanEqual($min, ?string $message = null, callable $callback = null);

    /**
     * validate specific value's length to be greater than a value
     *
     * @param $min
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function greaterThanLength($min, ?string $message = null, callable $callback = null);

    /**
     * validate specific name to be greater than a value
     *
     * @param $min
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function greaterThan($min, ?string $message = null, callable $callback = null);

    /**
     * Validate a hex color to be in [0-f0-9] 3 or 6 characters
     * with or without hash tag sign (#)
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function hexColor(?string $message = null, callable $callback = null);

    /**
     * Check if a field value is in a list or not in strict or non strict mode comparison
     *
     * @param array $list
     * @param string|null $message
     * @param callable $callback
     * @param bool $strict
     * @return static
     */public function isIn(array $list, ?string $message = null, callable $callback = null, bool $strict = true);

    /**
     * Check if a value is integer
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function isInteger(?string $message = null, callable $callback = null);

    /**
     * Check if value is a valid ip-v4
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function ipv4(?string $message = null, callable $callback = null);

    /**
     * Check if value is a valid ip-v6
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function ipv6(?string $message = null, callable $callback = null);

    /**
     * Check if value is a valid ip
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function ip(?string $message = null, callable $callback = null);

    /**
     * Check if value is checked
     *
     * Note: Following values considered as checked:
     *   [
     *     'yes',
     *     'on',
     *     1,
     *     '1',
     *     true
     *   ]
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function isChecked(?string $message = null, callable $callback = null);

    /**
     * Check if string value's length between two numeric values
     *
     * @param $min
     * @param $max
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function lengthBetween($min, $max, ?string $message = null, callable $callback = null);

    /**
     * validate specific value's length to be less than or equal to a value
     *
     * @param $max
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function lessThanEqualLength($max, ?string $message = null, callable $callback = null);

    /**
     * validate specific name to be less than or equal to a value
     *
     * @param $max
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function lessThanEqual($max, ?string $message = null, callable $callback = null);

    /**
     * validate specific value's length to be less than a value
     *
     * @param $max
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function lessThanLength($max, ?string $message = null, callable $callback = null);

    /**
     * validate specific name to be less than a value
     *
     * @param $max
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function lessThan($max, ?string $message = null, callable $callback = null);

    /**
     * Check if numeric value between two other numeric values
     *
     * @param $min
     * @param $max
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function between($min, $max, ?string $message = null, callable $callback = null);

    /**
     * Validate a specific regex
     *
     * @param $regex
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function regex($regex, ?string $message = null, callable $callback = null);

    /**
     * Validate specific name to be required
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function required(?string $message = null, callable $callback = null);

    /**
     * Validate specific name to be required
     * if all of $names parameter are present.
     *
     * @param array|string $names
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function requiredWithAll($names, ?string $message = null, callable $callback = null);

    /**
     * Validate specific name to be required
     * if at least one of $names parameter is present.
     *
     * @param array|string $names
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function requiredWith($names, ?string $message = null, callable $callback = null);

    /**
     * Check if a timestamp is valid or not
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function timestamp(?string $message = null, callable $callback = null);

    /**
     * Check if an array of values is unique
     *
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     */
    public function isUnique(?string $message = null, callable $callback = null);

    /**
     * Check if a url is valid or not
     *
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function url(?string $message = null, callable $callback = null);

    /**
     * Validate if $fstName field is equals to $sndName field
     *
     * @param string|array $fstName
     * @param string|array $sndName
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function match($fstName, $sndName, ?string $message = null, callable $callback = null);

    /**
     * Check if a file is already exists in a directory
     *
     * @param string $file - Absolute path
     * @param string|null $message
     * @param callable $callback
     * @return static
     */
    public function fileDuplicate(string $file, ?string $message = null, callable $callback = null);

    /**
     * Create a custom validation with a callable parameter
     * for a specific field
     *
     * @param callable $callback
     * @param string|null $message
     * @return static
     */
    public function custom(callable $callback, string $message);

    /**
     * Set fields name(s) to validate them
     *
     * @param array|string $fields
     * @return static
     */
    public function setFields($fields);

    /**
     * Append fields name(s) to validate them
     *
     * @param $fields
     * @return mixed
     */
    public function addToFields($fields);

    /**
     * Set a name for validating multi form and prevent conflict on get or set error for them
     *
     * @param string|null $formName
     * @return static
     */
    public function setFormName(?string $formName);

    /**
     * @return string
     */
    public function getFormName(): string;

    /**
     * Returns validation status of all validations
     *
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param array|string $name
     * @param string $message
     * @return static
     */
    public function setError($name, $message);

    /**
     * Reset form variables without store previous status
     */
    public function reset(): void;
}