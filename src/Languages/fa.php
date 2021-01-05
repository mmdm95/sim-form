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
    'alphaNum' => '{alias} باید دارای حروف و اعداد باشد.',
    'alpha' => '{alias} باید دارای حروف باشد.',
    'email' => 'ایمیل نامعتبر است.',
    'equalLength' => 'تعداد حروف {alias} باید  {length} باشد.',
    'equal' => '{alias} باید برابر با {compareTo} باشد.',
    'isFloat' => '{alias} باید عدد اعشاری باشد.',
    'greaterThanEqualLength' => 'تعداد حروف {alias} باید مساوی یا بیشتر از {min} باشد.',
    'greaterThanEqual' => '{alias} باید مساوی یا بزرگتر از {min} باشد.',
    'greaterThanLength' => 'تعداد حروف {alias} باید از {min} بیشتر باشد.',
    'greaterThan' => '{alias} باید از {min} بزرگتر باشد.',
    'hexColor' => 'کد هگز {alias} نامعتبر است.',
    'isIn' => '{alias} در {list} وجود ندارد.',
    'isInteger' => '{alias} باید از نوع عددی باشد.',
    'ipv4' => '{alias} یک ipv4 نامعتبر است.',
    'ipv6' => '{alias} یک ipv6 نامعتبر است.',
    'ip' => '{alias} یک ip نامعتبر است.',
    'isChecked' => '{alias} علامت گذاری نشده است.',
    'lengthBetween' => 'تعداد حروف {alias} باید بین {min} و {max} باشد.',
    'lessThanEqualLength' => 'تعداد حروف {alias} باید مساوی یا کمتر از {max} باشد.',
    'lessThanEqual' => '{alias} باید مساوی یا کوچکتر از {max} باشد.',
    'lessThanLength' => 'تعداد حروف {alias} باید از {max} کمتر باشد.',
    'lessThan' => '{alias} باید از {max} کوچکتر باشد.',
    'between' => '{alias} باید بین {min} و {max} باشد.',
    'password' => 'کلمه عبور به اندازه کافی قوی نیست.',
    'regex' => 'عبارت منظم نامعتبر است.',
    'required' => '{alias} اجباری است.',
    'requiredWithAll' => '{alias} اجباری است.',
    'requiredWith' => '{alias} اجباری است.',
    'timestamp' => '{alias} یک زمان وارد شده نامعتبر است.',
    'isUnique' => '{alias} یک آرایه یکتا نمی باشد.',
    'url' => '{alias} یک آدرس url نامعتبر است.',
    'match' => 'مقدار {second} با مقدار {first} برابر نیست.',
    'fileDuplicate' => 'فایل {filename} وجود دارد.',
];
