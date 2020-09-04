<?php

namespace Sim\Form\Interfaces;

interface IFormError
{
    /**
     * Get all raw errors
     *
     * @return array
     */
    public function getRawErrors(): array;

    /**
     * Get specific/all unique error/errors by its name group
     *
     * Note:
     *   1. This function get unique errors for each individual name
     *      and not unique errors in general, for that purpose use getUniqueError() method
     *   2. To get specific errors for formName, you must set form name before use this function
     *
     * @param string|null $name
     * @param string|null $formName
     * @return array|mixed
     */
    public function getError(?string $name = null, ?string $formName = null);

    /**
     * Make all errors unique in general
     *
     * Note: To get specific errors for formName, you must set form name before use this function
     *
     * @param string|null $formName
     * @return array
     */
    public function getUniqueErrors($formName = null): array;

    /**
     * Get all error messages in formatted string.
     * It tries to make errors unique individually first
     *
     * Note: This function get formatted unique errors for each individual name
     *       and not unique errors in general, for that purpose use getFormattedUniqueErrors() method
     *
     * @param string $prefix
     * @param string $suffix
     * @param string|null $name
     * @param string|null $formName
     * @return string
     */
    public function getFormattedError($prefix = '<p>', $suffix = '</p>', $name = null, $formName = null): string;

    /**
     * Get all error messages in formatted string.
     * It tries to make errors unique in general
     *
     * @param string $prefix
     * @param string $suffix
     * @param string|null $formName
     * @return string
     */
    public function getFormattedUniqueErrors($prefix = '<p>', $suffix = '</p>', $formName = null): string;

    /**
     * Check if an error is set for $name recursively
     *
     * @param string $name
     * @return bool
     */
    public function haveErrorFor(string $name): bool;
}