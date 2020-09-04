<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class PersianNationalCodeValidation extends AbstractValidation
{
    protected $error_message = 'National code is invalid.';

    /**
     * Please specify [numeric value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_numeric($_[0])) {
            return false;
        }

        [$value] = $_;
        return $this->checkNationalCode($value);
    }

    /**
     * @param $code
     * @return bool
     */
    private function checkNationalCode($code)
    {
        if (!preg_match('/^[0-9]{10}$/', $code))
            return false;
        for ($i = 0; $i < 10; $i++)
            if (preg_match('/^' . $i . '{10}$/', $code))
                return false;
        for ($i = 0, $sum = 0; $i < 9; $i++)
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity))
            return true;
        return false;
    }
}