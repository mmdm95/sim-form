<?php

namespace Sim\Form\Abstracts;

use ReflectionClass;
use Sim\Form\Exceptions\FormException;
use Sim\Form\Exceptions\ValidationException;
use Sim\Form\Interfaces\IFormValidator;
use Sim\Form\Validations\AlphaNumericValidation;
use Sim\Form\Validations\AlphaValidation;
use Sim\Form\Validations\EmailValidation;
use Sim\Form\Validations\EqualLengthValidation;
use Sim\Form\Validations\EqualValidation;
use Sim\Form\Validations\FloatValidation;
use Sim\Form\Validations\GreaterThanEqualLengthValidation;
use Sim\Form\Validations\GreaterThanEqualValidation;
use Sim\Form\Validations\GreaterThanLengthValidation;
use Sim\Form\Validations\GreaterThanValidation;
use Sim\Form\Validations\InArrayValidation;
use Sim\Form\Validations\IntegerValidation;
use Sim\Form\Validations\IPV4Validation;
use Sim\Form\Validations\IPV6Validation;
use Sim\Form\Validations\IPValidation;
use Sim\Form\Validations\IsCheckedValidation;
use Sim\Form\Validations\LengthBetweenValidation;
use Sim\Form\Validations\LessThanEqualLengthValidation;
use Sim\Form\Validations\LessThanEqualValidation;
use Sim\Form\Validations\LessThanLengthValidation;
use Sim\Form\Validations\LessThanValidation;
use Sim\Form\Validations\NumberBetweenValidation;
use Sim\Form\Validations\PasswordValidation;
use Sim\Form\Validations\RegexValidation;
use Sim\Form\Validations\RequiredValidation;
use Sim\Form\Validations\RequiredWithAllValidation;
use Sim\Form\Validations\RequiredWithValidation;
use Sim\Form\Validations\TimestampValidation;
use Sim\Form\Validations\UniqueArrayValidation;
use Sim\Form\Validations\UrlValidation;

abstract class AbstractFormValidator extends AbstractFormErrorProvider implements IFormValidator
{
    /**
     * @var array $fields
     */
    protected $fields = [];

    /**
     * Store all fields that are set during validation uniquely
     *
     * @var array $all_fields
     */
    protected $all_fields = [];

    /**
     * @var string|null $form_name
     */
    protected $form_name = null;

    /**
     * General status of validation
     *
     * @var bool $status
     */
    protected $status = true;

    /**
     * Store all previous status
     *
     * @var array $previous_status
     */
    protected $previous_status = [];

    /**
     * @var array $validator_classes
     */
    protected $extend_validator_classes = [];

    /**
     * @var array $validator_classes
     */
    protected $validator_classes = [
        'alphaNum' => AlphaNumericValidation::class,
        'alpha' => AlphaValidation::class,
        'email' => EmailValidation::class,
        'equalLength' => EqualLengthValidation::class,
        'equal' => EqualValidation::class,
        'isFloat' => FloatValidation::class,
        'greaterThanEqualLength' => GreaterThanEqualLengthValidation::class,
        'greaterThanEqual' => GreaterThanEqualValidation::class,
        'greaterThanLength' => GreaterThanLengthValidation::class,
        'greaterThan' => GreaterThanValidation::class,
        'isIn' => InArrayValidation::class,
        'isInteger' => IntegerValidation::class,
        'ipv4' => IPV4Validation::class,
        'ipv6' => IPV6Validation::class,
        'ip' => IPValidation::class,
        'isChecked' => IsCheckedValidation::class,
        'lengthBetween' => LengthBetweenValidation::class,
        'lessThanEqualLength' => LessThanEqualLengthValidation::class,
        'lessThanEqual' => LessThanEqualValidation::class,
        'lessThanLength' => LessThanLengthValidation::class,
        'lessThan' => LessThanValidation::class,
        'between' => NumberBetweenValidation::class,
        'password' => PasswordValidation::class,
        'regex' => RegexValidation::class,
        'required' => RequiredValidation::class,
        'requiredWithAll' => RequiredWithAllValidation::class,
        'requiredWith' => RequiredWithValidation::class,
        'timestamp' => TimestampValidation::class,
        'isUnique' => UniqueArrayValidation::class,
        'url' => UrlValidation::class,
    ];

    /**
     * @var array $validator_classes_instances
     */
    protected $validator_classes_instances = [];

    /**
     * @var string $language_directory
     */
    protected $language_directory = __DIR__ . '/../Languages/';

    /**
     * @var string $language_type
     */
    protected $language_type = 'en';

    /**
     * @var array $language_settings
     */
    protected $language_settings = [];

    /**
     * AbstractFormValidator constructor.
     */
    public function __construct()
    {
        $this->_load_language_settings();
    }

    /**
     * @param string $dir
     * @return static
     */
    public function setLangDir(string $dir)
    {
        if (file_exists($dir) && is_dir($dir)) {
            $dir = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $dir);
            $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $this->language_directory = $dir;
        }
        return $this;
    }

    /**
     * @param string $lang
     * @return static
     */
    public function setLang(string $lang)
    {
        if (!empty($lang)) {
            $this->language_directory = $lang;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFields($fields)
    {
        $this->fields = [];
        if (is_array($fields)) {
            $this->fields = $fields;
        } elseif (is_string($fields)) {
            $this->fields = [$fields];
        }
        $this->all_fields = array_unique(array_merge($this->all_fields, $this->fields));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormName(string $formName)
    {
        if (is_null($this->form_name) || (is_string($formName) && !empty($formName))) {
            $this->form_name = $formName;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormName(): string
    {
        return $this->form_name;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): bool
    {
        return (bool)$this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setError($name, $message)
    {
        if (is_array($name)) {
            foreach ($name as $value) {
                if (is_string($message) && !empty($message)) {
                    $this->_set_error($value, $message);
                }
            }
        } else {
            if (is_string($message) && !empty($message)) {
                $this->_set_error($name, $message);
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->fields = [];
        $this->all_fields = [];
        $this->form_name = null;
        $this->status = true;
        $this->errors = [];
    }

    /**
     * Clone magic method, resets variables for reuse purpose
     * and store previous status
     */
    public function __clone()
    {
        // Store previous status of validation
        if (is_string($this->form_name)) {
            $this->previous_status[$this->form_name] = $this->getStatus();
        } else {
            $this->previous_status[] = $this->getStatus();
        }

        $this->reset();
    }

    /**
     * Check for alpha numeric string
     *
     * @param $str
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _alpha_num($str): bool
    {
        /**
         * @var AlphaNumericValidation $instance
         */
        $instance = $this->getInstanceOf('alphaNum');
        return $instance->validate($this->_to_scalar($str));
    }

    /**
     * Check for alpha string
     *
     * @param $str
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _alpha($str): bool
    {
        /**
         * @var AlphaValidation $instance
         */
        $instance = $this->getInstanceOf('alpha');
        return $instance->validate($this->_to_scalar($str));
    }

    /**
     * Check if an email is valid or not
     *
     * @param $email
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _email($email): bool
    {
        /**
         * @var EmailValidation $instance
         */
        $instance = $this->getInstanceOf('email');
        return $instance->validate($email);
    }

    /**
     * If a $str length equal to $length or not
     *
     * @param string $str
     * @param int $length
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _equal_length($str, $length): bool
    {
        /**
         * @var EqualLengthValidation $instance
         */
        $instance = $this->getInstanceOf('equalLength');
        return $instance->validate($this->_to_scalar($str), $length);
    }

    /**
     * If a numeric value or a string value is equals to $secondValue or not
     *
     * @param int|string $value
     * @param int|string $secondValue
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _equal($value, $secondValue): bool
    {
        /**
         * @var EqualValidation $instance
         */
        $instance = $this->getInstanceOf('equal');
        return $instance->validate($this->_to_scalar($value), $this->_to_scalar($secondValue));
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_float($value): bool
    {
        /**
         * @var FloatValidation $instance
         */
        $instance = $this->getInstanceOf('isFloat');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @param $min
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _greater_than_equal_length($value, $min): bool
    {
        /**
         * @var GreaterThanEqualLengthValidation $instance
         */
        $instance = $this->getInstanceOf('greaterThanEqualLength');
        return $instance->validate($this->_to_scalar($value), $min);
    }

    /**
     * If a numeric value or a string value is greater than or equal $min or not
     *
     * @param int|string $value
     * @param int $min
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _greater_than_equal($value, $min): bool
    {
        /**
         * @var GreaterThanEqualValidation $instance
         */
        $instance = $this->getInstanceOf('greaterThanEqual');
        return $instance->validate($value, $min);
    }

    /**
     * @param $value
     * @param $min
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _greater_than_length($value, $min): bool
    {
        /**
         * @var GreaterThanLengthValidation $instance
         */
        $instance = $this->getInstanceOf('greaterThanLength');
        return $instance->validate($this->_to_scalar($value), $min);
    }

    /**
     * If a numeric value or a string value is greater than $min or not
     *
     * @param int|string $value
     * @param int $min
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _greater_than($value, $min): bool
    {
        /**
         * @var GreaterThanValidation $instance
         */
        $instance = $this->getInstanceOf('greaterThan');
        return $instance->validate($value, $min);
    }

    /**
     * If a value is in a array list or not
     *
     * @param $value
     * @param array $list
     * @param bool $strict
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_in($value, $list, bool $strict = false): bool
    {
        /**
         * @var InArrayValidation $instance
         */
        $instance = $this->getInstanceOf('isIn');

        // if is not array, convert to scalar value then
        if(!is_array($value)) $value = $this->_to_scalar($value);
        return $instance->validate($value, $list, $strict);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_integer($value): bool
    {
        /**
         * @var IntegerValidation $instance
         */
        $instance = $this->getInstanceOf('isInteger');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_ipv4($value): bool
    {
        /**
         * @var IPV4Validation $instance
         */
        $instance = $this->getInstanceOf('ipv4');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_ipv6($value): bool
    {
        /**
         * @var IPV6Validation $instance
         */
        $instance = $this->getInstanceOf('ipv6');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_ip($value): bool
    {
        /**
         * @var IPValidation $instance
         */
        $instance = $this->getInstanceOf('ip');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_checked($value): bool
    {
        /**
         * @var IsCheckedValidation $instance
         */
        $instance = $this->getInstanceOf('isChecked');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_length_between($value, $min, $max): bool
    {
        /**
         * @var LengthBetweenValidation $instance
         */
        $instance = $this->getInstanceOf('lengthBetween');
        return $instance->validate($this->_to_scalar($value), $min, $max);
    }

    /**
     * @param $value
     * @param $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _less_than_equal_length($value, $max): bool
    {
        /**
         * @var LessThanEqualLengthValidation $instance
         */
        $instance = $this->getInstanceOf('lessThanEqualLength');
        return $instance->validate($this->_to_scalar($value), $max);
    }

    /**
     * If a numeric value or a string value is less than or equal $max or not
     *
     * @param int|string $value
     * @param int $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _less_than_equal($value, $max): bool
    {
        /**
         * @var LessThanEqualValidation $instance
         */
        $instance = $this->getInstanceOf('lessThanEqual');
        return $instance->validate($value, $max);
    }

    /**
     * @param $value
     * @param $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _less_than_length($value, $max): bool
    {
        /**
         * @var LessThanLengthValidation $instance
         */
        $instance = $this->getInstanceOf('lessThanLength');
        return $instance->validate($this->_to_scalar($value), $max);
    }

    /**
     * If a numeric value or a string value is less than $max or not
     *
     * @param int|string $value
     * @param int $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _less_than($value, $max): bool
    {
        /**
         * @var LessThanValidation $instance
         */
        $instance = $this->getInstanceOf('lessThan');
        return $instance->validate($value, $max);
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _between($value, $min, $max): bool
    {
        /**
         * @var NumberBetweenValidation $instance
         */
        $instance = $this->getInstanceOf('between');
        return $instance->validate($this->_to_scalar($value), $min, $max);
    }

    /**
     * Validate password with optional strength
     *
     * @param $value
     * @param $strength
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _password($value, $strength): bool
    {
        /**
         * @var PasswordValidation $instance
         */
        $instance = $this->getInstanceOf('password');
        return $instance->validate($this->_to_scalar($value), $strength);
    }

    /**
     * If a regex is valid or not
     *
     * @param string $str
     * @param $regex
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _regex($str, $regex): bool
    {
        /**
         * @var RegexValidation $instance
         */
        $instance = $this->getInstanceOf('regex');
        return $instance->validate($this->_to_scalar($str), $regex);
    }

    /**
     * Check if a value is not empty
     *
     * @param array|string $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _required($value): bool
    {
        /**
         * @var RequiredValidation $instance
         */
        $instance = $this->getInstanceOf('required');

        // if is not array, convert to scalar value then
        if(!is_array($value)) $value = $this->_to_scalar($value);
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @param $names
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _required_with_all($value, $names): bool
    {
        /**
         * @var RequiredWithAllValidation $instance
         */
        $instance = $this->getInstanceOf('requiredWithAll');

        // if is not array, convert to scalar value then
        if(!is_array($value)) $value = $this->_to_scalar($value);
        return $instance->validate($value, ...$names);
    }

    /**
     * @param $value
     * @param $names
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _required_with($value, $names): bool
    {
        /**
         * @var RequiredWithValidation $instance
         */
        $instance = $this->getInstanceOf('requiredWith');

        // if is not array, convert to scalar value then
        if(!is_array($value)) $value = $this->_to_scalar($value);
        return $instance->validate($value, ...$names);
    }

    /**
     * Check if a timestamp is valid or not
     *
     * @param $timestamp
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _timestamp($timestamp): bool
    {
        /**
         * @var TimestampValidation $instance
         */
        $instance = $this->getInstanceOf('timestamp');
        return $instance->validate($this->_to_scalar($timestamp));
    }

    /**
     * @param $array
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _is_unique($array): bool
    {
        /**
         * @var UniqueArrayValidation $instance
         */
        $instance = $this->getInstanceOf('isUnique');
        return $instance->validate($array);
    }

    /**
     * Check if a url is valid or not
     *
     * @param $url
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _url($url): bool
    {
        /**
         * @var UrlValidation $instance
         */
        $instance = $this->getInstanceOf('url');
        return $instance->validate($url);
    }

    /**
     * Set status according to previous status
     *
     * @param bool $status
     */
    protected function _set_status($status)
    {
        $this->status = $this->status && $status;
    }

    /**
     * Check if fields are set or not
     * and etc.
     *
     * @throws FormException
     */
    protected function assertRequirements()
    {
        if (empty($this->fields)) {
            throw new FormException('You must specify fields name to validate them.');
        }
    }

    /**
     * Set error for a name with given message
     *
     * @param string $name
     * @param $message
     */
    private function _set_error($name, $message)
    {
        if (is_string($this->form_name)) {
            if (!isset($this->errors[$this->form_name][$name]) || !is_array($this->errors[$this->form_name][$name])) {
                $this->errors[$this->form_name][$name] = [];
            }
            if (!in_array($message, $this->errors[$this->form_name][$name])) {
                $this->errors[$this->form_name][$name][] = $message;
            }
        } else {
            if (!isset($this->errors[$name])) {
                $this->errors[$name] = [];
            }
            if (!in_array($message, $this->errors[$name])) {
                $this->errors[$name][] = $message;
            }
        }
    }

    /**
     * @param $value
     * @return string
     */
    protected function _to_scalar($value)
    {
        if (is_null($value)) return '';
        if (is_array($value) || is_object($value) || is_resource($value)) return strval($value);
        if (!is_scalar($value)) return (string)$value;
        return $value;
    }

    /**
     * Load language settings
     */
    protected function _load_language_settings()
    {
        $ext = pathinfo($this->language_type, \PATHINFO_EXTENSION);
        if (empty($ext)) {
            $this->language_type .= '.php';
        }

        $filename = $this->language_directory . $this->language_type;
        if (file_exists($filename)) {
            $this->language_settings = include $filename . '';
        }
    }

    /**
     * @param $name
     * @return mixed|object
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function getInstanceOf($name)
    {
        if (!isset($this->validator_classes[$name]) && !isset($this->extend_validator_classes[$name])) {
            throw new ValidationException(sprintf('Class %s not exists in validation classes', $name));
        }

        if (isset($this->validator_classes_instances[$name])) {
            return $this->validator_classes_instances[$name];
        }

        $reflector = new ReflectionClass($this->validator_classes[$name] ?? $this->extend_validator_classes[$name]);
        if (!$reflector->isInstantiable()) { // Check if class is instantiable
            throw new ValidationException(sprintf('Class %s is not instantiable', $name));
        }

        $instance = $reflector->newInstance();
        $this->validator_classes_instances[$name] = $instance;

        return $instance;
    }
}