<?php

use Sim\Form\FormValidator\FormValidator;

include_once '../../vendor/autoload.php';

$form_validator = new FormValidator([
    'settings' => [
        ['threshold' => 50],
        ['threshold' => 80],
    ],
]);
//$form_validator = new FormValidator([
//    'settings' => [
//        'threshold' => 50,
//        'awesome' => 90,
//    ]
//]);
//$form_validator = new FormValidator(array('values' => array(50, 90)));

//$form_validator->setFields(['values.*']);
//$form_validator->setFields(['settings.*']);

$form_validator->setFields(['threshold' => 'settings.*.threshold']);
$form_validator->lessThanEqual(80);

var_dump('form validation is: ' . ($form_validator->getStatus() ? 'OK ($+$)' : 'Bad (\'-\')'));
echo PHP_EOL;
var_dump($form_validator->getError());
