<?php

use Sim\Form\FormElements\Button;
use Sim\Form\FormElements\Form;
use Sim\Form\FormElements\Input;
use Sim\Form\FormElements\SimpleText;
use Sim\Form\FormElements\Wrapper;
use Sim\Form\FormValidator;
use Sim\Form\FormValue;
use Sim\Form\Interfaces\IFormError;

$form_name = 'test-form';

// initialize form object
$form = new Form($form_name, 'index.php', 'post');

// create a first name input
$first_name_inp = new Input('first_name', 'text');
$first_name_inp->label()->add(new SimpleText('First name:'));
$first_name_inp->setAttributes([
    'placeholder' => 'first name',
    'required' => 'true',
]);

// a simple wrapper
$wrapper = new Wrapper();
$wrapper->add($first_name_inp);

// create a last name input
$last_name_inp = new Input('last_name', 'text');
$last_name_inp->label()->add(new SimpleText('Last name:'));
$last_name_inp->setAttribute('placeholder', 'last name');

// another simple wrapper
$wrapper2 = new Wrapper();
$wrapper2->add($last_name_inp);

// create a mobile numbers input
$mobile_1 = new Input('mobile[][user]', 'text');
$mobile_1->label()->add(new SimpleText('Mobile 1:'));
$mobile_1->setAttribute('placeholder', 'first mobile');

// another simple wrapper
$wrapper3 = new Wrapper();
$wrapper3->add($mobile_1);

// create a mobile numbers input
$mobile_2 = new Input('mobile[][user]', 'text');
$mobile_2->label()->add(new SimpleText('Mobile 2:'));
$mobile_2->setAttribute('placeholder', 'second mobile');

// another simple wrapper
$wrapper4 = new Wrapper();
$wrapper4->add($mobile_2);

// create a mobile numbers input
$password = new Input('password', 'password');
$password->label()->add(new SimpleText('Password:'));
$password->setAttribute('placeholder', 'password');

// another simple wrapper
$wrapper5 = new Wrapper();
$wrapper5->add($password);

// create a mobile numbers input
$rePassword = new Input('re_password', 'password');
$rePassword->label()->add(new SimpleText('Password repeat:'));
$rePassword->setAttribute('placeholder', 'password repeat');

// another simple wrapper
$wrapper6 = new Wrapper();
$wrapper6->add($rePassword);

// create a simple button with expandable element
$submit_btn = new Button('submit', 'submit');
$submit_btn->add(new SimpleText('<em>Submit</em>'));

$form->add($wrapper)
    ->add($wrapper2)
    ->add($wrapper3)
    ->add($wrapper4)
    ->add($wrapper5)
    ->add($wrapper6)
    ->add($submit_btn);

// add some validation rules to inputs
$form->validateClosure(function (FormValidator $validator) use ($form_name) {
    $validator->setFormName($form_name)->setFields([
        'first_name', 'last_name'
    ])->alpha(null, function (FormValue $value) {
        if($value->getName() == 'first_name') {
//            echo 'I am ' . $value->getName() . PHP_EOL;
            $value->replaceValue('MM');
        }
//        var_dump($value->getValue(), $value->getName(), $value->getAlias());
//        echo PHP_EOL;
    });
     // because of setting
    $validator->setFieldsAlias([
        'first_name' => 'First name',
        'last_name' => 'Last name',
    ]);

//    $validator->stopValidationAfterFirstErrorOnEachFieldGroup(true);
    $validator->setFields(['name' => 'first_name' ,'last_name'])->greaterThanEqualLength(3);
    $validator->setFields(['first_name'])->regex('/[0-9]/');
//    $validator->stopValidationAfterFirstError(true);
    $validator->setFields(['mobile' => 'mobile.*.user'])->custom(function (FormValue $value) {
        $mobileValidator = new \Sim\Form\Validations\PersianMobileValidation();
        return $mobileValidator->validate($value->getValue());
    }, 'Mobile is not valid')->equalLength(11);
    $validator->match(['password' => 'password'], ['password repeat' => 're_password']);
});

$form_errors = null;

if (isset($_POST['submit'])) {
//    $form->haveIndividualErrors(true);
    if($form->validate()) {
        echo 'valid form';
    } else {
        /**
         * @var IFormError
         */
        $form_errors = $form->getValidationErrors();
    }
}

$form_content = $form->render();
