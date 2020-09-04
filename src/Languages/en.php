<?php

/**
 * Structure is like:
 * [
 *   method_name like required => message like '{alias} is required' or something like that
 * ]
 *
 * Allowed placeholders:
 * {alias} - alias of the input
 * {name} - name of the input
 * {value} - value of the input
 *
 * Note:
 *   If alias is not specified, name will be alias then.
 */
return [
    'alphaNum' => '{alias} should have alpha and numeric',
    'alpha' => '{alias} should have alpha',
    'email' => '{alias} is not a valid email',
    'equalLength' => '{alias} length is not equal to {length}',
    'equal' => '{alias} is not equal to {compareTo}',
    'isFloat' => '{alias} is not float',
    'greaterThanEqualLength' => '{alias} length is not greater than or equal to {min}',
    'greaterThanEqual' => '{alias} is not greater than or equal to {min}',
    'greaterThanLength' => '{alias} length is not greater than {min}',
    'greaterThan' => '{alias} is not greater than {min}',
    'isIn' => '{alias} is not in list',
    'isInteger' => '{alias} is not integer',
    'ipv4' => '{alias} is not a valid ipv4',
    'ipv6' => '{alias} is not a valid ipv6',
    'ip' => '{alias} is not a valid ip',
    'isChecked' => '{alias} is not checked',
    'lengthBetween' => '{alias} length should be between {min} and {max}',
    'lessThanEqualLength' => '{alias} length is not less than or equal to {max}',
    'lessThanEqual' => '{alias} is not less than or equal to {max}',
    'lessThanLength' => '{alias} length is not less than {max}',
    'lessThan' => '{alias} is not less than {max}',
    'between' => '{alias} should be between {min} and {max}',
    'password' => '{alias} is not a good password',
    'regex' => '{alias} is not in valid format',
    'required' => '{alias} is required',
    'requiredWithAll' => '{alias} is required',
    'requiredWith' => '{alias} is required',
    'timestamp' => '{alias} is not valid timestamp',
    'isUnique' => '{alias} is not unique array',
    'url' => '{alias} is not a valid url',
    'match' => '{second} is not equal to {first}',
    'fileDuplicate' => '{alias} is already exists',
];
