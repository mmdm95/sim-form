<?php

use Sim\Form\FormValidator;

include_once '../../vendor/autoload.php';

$form_validator = new FormValidator();

if ($_POST && count($_POST)) {
    $form_validator
        ->setFieldsAlias([
            'chk' => 'Dummy checkbox',
            'multi-select' => 'Dummy multi select',
            'name' => 'Name',
            'family' => 'Family',
            'mobile' => 'Mobile number',
            'mobile2' => 'Second mobile number',
        ])->setDefaultValue([
            'chk' => 'off',
        ])
        ->toEnglishValue(true)
        ->toEnglishValueExceptFields([
            'mobile',
        ]);

    // multi select
    $form_validator
        ->setFields('multi-select')
        ->required();
    // name
    $form_validator
        ->setFields('name')
        ->alpha();
    // family
    $form_validator
        ->setFields('family')
        ->alpha();
    // mobile
    $form_validator
        ->setFields([
            'mobile',
            'mobile2'
        ])
        ->regex('/^(098|\+98|0)?9\d{9}$/');

    // to reset form values and not set them again
    if ($form_validator->getStatus()) {
        $form_validator->resetBagValues();
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple validation test</title>
</head>
<body>
<form action="simple.php" method="post">
    <?php if (!$form_validator->getStatus()): ?>
        <div style="background-color: #f2f3f1; padding: 15px; margin-bottom: 15px;">
            <?= $form_validator->getFormattedUniqueErrors(); ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 15px;">
        <label for="chk1">Check me!</label>
        <input id="chk1" type="checkbox" name="chk"
            <?= $form_validator->setCheckbox('chk', 'on', true); ?>>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="multiSelect">Choose some:</label>
        <select id="multiSelect" name="multi-select[]" multiple>
            <option value="1" <?= $form_validator->setSelect('multi-select', '1'); ?>>A</option>
            <option value="2" <?= $form_validator->setSelect('multi-select', '2'); ?>>B</option>
            <option value="3" <?= $form_validator->setSelect('multi-select', '3'); ?>>C</option>
            <option value="4" <?= $form_validator->setSelect('multi-select', '4'); ?>>D</option>
        </select>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="name">Name:</label>
        <input id="name" type="text" name="name"
               value="<?= $form_validator->setInput('name'); ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="family">Family:</label>
        <input id="family" type="text" name="family"
               value="<?= $form_validator->setInput('family'); ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="mobile">Mobile number:</label>
        <input id="mobile" type="text" name="mobile"
               value="<?= $form_validator->setInput('mobile'); ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="mobile2">Second mobile number:</label>
        <input id="mobile2" type="text" name="mobile2"
               value="<?= $form_validator->setInput('mobile2'); ?>">
    </div>

    <button type="submit">
        Submit
    </button>
</form>
</body>
</html>