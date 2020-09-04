<?php

namespace Sim\Form\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class PasswordValidation extends AbstractValidation
{
    const STRENGTH_EASY = 1;
    const STRENGTH_NORMAL = 2;
    const STRENGTH_HARD = 3;
    const STRENGTH_INSANE = 4;

    protected $error_message = 'Password is invalid.';

    /**
     * Please specify [numeric value] and optional [numeric strength]
     * from constants of PasswordValidation class to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if(count($_) < 1 || !is_numeric($_[0])) {
            return false;
        }

        [$value] = $_;
        $strength = $_[1] ?? PasswordValidation::STRENGTH_EASY;

        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $value);
        $lowercase = preg_match('@[a-z]@', $value);
        $number = preg_match('@[0-9]@', $value);
        $specialChars = preg_match('@[^\w]@', $value);

        if (PasswordValidation::STRENGTH_EASY == $strength) {
            if($lowercase) {
                return true;
            } else {
                $this->setError('Password should have at least lowercase characters');
            }
        } else if (PasswordValidation::STRENGTH_NORMAL == $strength) {
            if($lowercase && $number) {
                return true;
            } else {
                $this->setError('Password should have at least lowercase and numeric characters');
            }
        } else if (PasswordValidation::STRENGTH_HARD == $strength) {
            if($lowercase && $number && $uppercase) {
                return true;
            } else {
                $this->setError('Password should have at least lowercase, uppercase and numeric characters');
            }
        } else if (PasswordValidation::STRENGTH_INSANE == $strength) {
            if($lowercase && $number && $uppercase && $specialChars) {
                return true;
            } else {
                $this->setError('Password should have lowercase, uppercase, numeric and special characters');
            }
        }
        return false;
    }
}