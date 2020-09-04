<?php

namespace Sim\Form\ValidationExtend;

use Sim\Form\FormValidator\FormValidator;

abstract class AbstractCustomValidation extends FormValidator
{
    /**
     * Add your validation classes like following structure
     * [
     *    method_name like required => class name with namespace like RequiredValidation::class,
     *    ...
     * ]
     *
     * Note: You can specify anything other than method_name but it is a nice
     *       solution to prevent conflict
     *
     * @var array $validator_classes
     */
    protected $extend_validator_classes = [

    ];
}