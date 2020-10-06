<?php

namespace Sim\Form\FormElements;

use Closure;
use Sim\Form\Abstracts\AbstractFieldComposite;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormErrorProvider;
use Sim\Form\FormValidator;
use Sim\Form\Interfaces\IFormElement;
use Sim\Form\Interfaces\IFormError;
use Sim\Form\Utils\ValidatorUtil;

class Form extends AbstractFieldComposite
{
    /**
     * @var string $method
     */
    protected $method = 'post';

    /**
     * @var bool $has_individual_errors
     */
    protected $has_individual_errors = false;

    /**
     * @var FormValidator|null $form_validator
     */
    protected $form_validator = null;

    /**
     * @var Closure|null $validate_closure
     */
    protected $validate_closure = null;

    /**
     * @var array $name_counter
     */
    private $name_counter = [];

    /**
     * Form constructor.
     * @param string|null $name
     * @param string|null $url
     * @param string|null $method
     */
    public function __construct(string $name = null, string $url = null, string $method = null)
    {
        parent::__construct('form', $name);
        $this->setAction($url);
        $this->setMethod($method);
    }

    /**
     * @param string $url
     * @return static
     */
    public function setAction(string $url)
    {
        if (!is_null($url)) {
            $this->attributes['action'] = $url;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getFromAttribute('action');
    }

    /**
     * @param string $method
     * @return static
     */
    public function setMethod(string $method)
    {
        if (!is_null($method)) {
            $this->attributes['method'] = $method;
        } else {
            $this->attributes['method'] = $this->method;
        }
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMethod()
    {
        return $this->getFromAttribute('method');
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function haveIndividualErrors(bool $answer)
    {
        $this->has_individual_errors = $answer;
        return $this;
    }

    /**
     * @param Closure $callback
     */
    public function validateClosure(Closure $callback)
    {
        $this->validate_closure = $callback;
    }

    /**
     * Validate form fields with passed closure
     *
     * @see Form::validateClosure()
     * @return bool
     * @throws FormException
     */
    public function validate(): bool
    {
        return $this->validateWithValues($_POST + $_FILES);
    }

    /**
     * Validate form fields with passed closure and passed values
     *
     * @param array $values
     * @return bool
     * @throws FormException
     */
    public function validateWithValues(array $values): bool
    {
        if (is_null($this->validate_closure)) {
            throw new FormException('Validation closure needed for validation');
        }

        if (is_null($this->form_validator) || !($this->form_validator instanceof FormValidator)) {
            $this->form_validator = new FormValidator($values);
        }
        call_user_func_array($this->validate_closure, [&$this->form_validator]);

        return $this->form_validator->getStatus();
    }

    /**
     * @return IFormError|null
     */
    public function getValidationErrors()
    {
        if (!is_null($this->form_validator) && $this->form_validator instanceof FormValidator) {
            return new FormErrorProvider($this->form_validator->getRawErrors());
        }
        return null;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        // reset counter
        $this->name_counter = [];

        $this->bubbleErrorsThroughElements($this->getComponents());
        $output = parent::render();
        return "<{$this->getTagName()} {$this->attributesToString()}>\n$output</{$this->getTagName()}>\n";
    }

    /**
     * Notify all component of form to set value and show error
     * if $has_individual_errors is <em>true</em>
     *
     * @param $components
     */
    protected function bubbleErrorsThroughElements($components)
    {
        if (is_null($this->form_validator)) return;
        if (!count($this->form_validator->getRawErrors())) return;

        /**
         * @var IFormElement $component
         */
        foreach ($components as $identifier => $component) {
            if ($component instanceof AbstractFieldComposite) {
                $this->bubbleErrorsThroughElements($component->getComponents());
            }

            $name = $component->getName();
            if (!empty($name)) {
                if ($this->form_validator->haveErrorFor(ValidatorUtil::normalizeFieldKey($name))) {
                    if ($this->has_individual_errors) {
                        $component->haveError(true);
                        $errors = $this->form_validator->getError(ValidatorUtil::normalizeFieldKey($name), $this->form_validator->getFormName());

                        // count empty messages
                        $simpleCounter = 0;
                        foreach ($errors as $k => $error) {
                            if (!empty($error)) {
                                $component->errorElement()->add((new Wrapper('div'))->add(new SimpleText($error)));
                            } else {
                                $simpleCounter++;
                            }
                        }

                        // if we have at least one message
                        if(count($errors) != $simpleCounter) {
                            if (!is_null($component->getParent())) {
                                $component->getParent()->add($component->errorElement());
                            }
                        }
                    }
                }

                if (!$this->form_validator->getStatus()) {
                    $params = $this->form_validator->getFieldValue(ValidatorUtil::normalizeFieldKey($name));
                    $param = $params;
                    if (is_array($params)) {
                        if (!isset($this->name_counter[$name])) {
                            $this->name_counter[$name] = 0;
                        } elseif ($this->name_counter[$name] < count($params)) {
                            $this->name_counter[$name]++;
                        }

                        $param = $params[$this->name_counter[$name]];
                    }

                    $component->setValue(strval($param));
                }
            }
        }
    }
}