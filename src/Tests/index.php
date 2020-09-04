<?php
include_once '../../vendor/autoload.php';
include_once 'testElement.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tests - index</title>
</head>
<body>
<?= !is_null($form_errors) ? $form_errors->getFormattedError() : ''; ?>
<?= $form_content; ?>
</body>
</html>
