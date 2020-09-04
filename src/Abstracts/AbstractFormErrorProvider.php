<?php

namespace Sim\Form\Abstracts;

use Sim\Form\Interfaces\IFormError;

abstract class AbstractFormErrorProvider implements IFormError
{
    /**
     * It should use like this:
     *   [
     *     'name' => [
     *       'error1',
     *       'error2',
     *       ...
     *     ],
     *     ...
     *   ]
     *
     * @var array $errors
     */
    protected $errors = [];

    /**
     * {@inheritdoc}
     */
    public function getRawErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getError(?string $name = null, ?string $formName = null)
    {
        if (!empty($name)) {
            if (is_string($formName)) {
                return $this->errors[$formName][$name] ?? [];
            } else {
                return $this->errors[$name] ?? [];
            }
        }

        $errors = is_string($formName) && isset($this->errors[$formName]) ? $this->errors[$formName] : $this->errors;
        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueErrors($formName = null): array
    {
        $newErrors = [];
        $errors = is_string($formName) && isset($this->errors[$formName]) ? $this->errors[$formName] : $this->errors;
        foreach ($errors as $name => $error) {
            foreach ($error as $err) {
                if (is_array($err)) {
                    foreach ($err as $e) {
                        $newErrors[] = $e;
                    }
                } elseif (is_string($err)) {
                    $newErrors[] = $err;
                }
            }
        }
        return $newErrors;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedError($prefix = '<p>', $suffix = '</p>', $name = null, $formName = null): string
    {
        $errorStr = '';
        $errors = $this->getError($name, $formName);
        if (is_array($errors)) {
            // Get all errors
            foreach ($errors as $key => $error) {
                foreach ($error as $err) {
                    if (is_array($err)) {
                        foreach ($err as $e) {
                            $errorStr .= $prefix . $e . $suffix;
                        }
                    } else {
                        $errorStr .= $prefix . $err . $suffix;
                    }
                }
            }
        } elseif (is_string($errors)) {
            // Get specific name errors
            return $prefix . $errors . $suffix;
        }
        return $errorStr;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedUniqueErrors($prefix = '<p>', $suffix = '</p>', $formName = null): string
    {
        $errorStr = '';
        $uniqueErrors = $this->getUniqueErrors($formName);
        foreach ($uniqueErrors as $error) {
            $errorStr .= $prefix . $error . $suffix;
        }

        return $errorStr;
    }

    /**
     * {@inheritdoc}
     */
    public function haveErrorFor(string $name): bool
    {
        return $this->hasErrorForRecursive($this->errors, $name);
    }

    /**
     * @param array $errors
     * @param string $name
     * @return bool
     */
    protected function hasErrorForRecursive(array $errors, string $name): bool
    {
        if (isset($errors[$name])) return true;
        foreach ($errors as $n => $error) {
            if (is_array($error)) {
                $res = $this->hasErrorForRecursive($error, $name);
                if (true === $res) return true;
            }
        }
        return false;
    }
}