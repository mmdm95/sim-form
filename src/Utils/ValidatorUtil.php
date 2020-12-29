<?php

namespace Sim\Form\Utils;

class ValidatorUtil
{
    /**
     * Convert some names like mobile[] to mobile.*
     * or user[][desc] to user.*.desc
     *
     * @param $key
     * @return string
     */
    public static function normalizeFieldKey($key): string
    {
        $key = preg_replace('#\[.?\]#', '.*', $key);
        $key = preg_replace('#\\[([^\[\]]+)\]#', '.$1', $key);
        return $key;
    }

    /**
     * Returns array of string values (empty or filled values)
     *
     * @param $data
     * @param array $names
     * @return array
     */
    public static function getParameters($data, ?array $names)
    {
        // if there is no $names parameter, return all data
        if (is_null($names) || !count($names)) {
            return [$data, false];
        }

        // if data isn't an array or object
        if (is_scalar($data)) {
            return [null, false];
        }

        // get first name to fetch
        $first = array_shift($names);

        if ('*' == $first) {
            $collection = [];
            foreach ($data as $v) {
                [$list, $isStar] = self::getParameters($v, $names);
                if ($isStar) {
                    $collection = array_merge($collection, $list);
                } else {
                    $collection[] = $list;
                }
            }
            return [$collection, true];
        } elseif (is_null($first) || !isset($data[$first])) {
            return [null, false];
        } elseif (count($names) == 0) {
            return [$data[$first], false];
        } else {
            return self::getParameters($data[$first], $names);
        }
    }

    /**
     * @param $array
     * @param $key
     * @param $value
     */
    public static function setToArray(&$array, $key, $value)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function getNRemoveFromArray(&$array, $key, $default = null)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return $default;
            }
            $array = &$array[$key];
        }
        $last = array_shift($keys);
        $res = $array[$last] ?? $default;
        if (isset($array[$last])) {
            unset($array[$last]);
        }
        return $res;
    }

    /**
     * @param $array
     * @param $key
     * @param bool $is_null_ok
     * @return bool
     */
    public static function hasInArray(&$array, $key, $is_null_ok = true): bool
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return false;
            }
            $array = &$array[$key];
        }
        $result = true;
        $last = array_shift($keys);
        if (!isset($array[$last])) $result = false;
        if ((bool)$is_null_ok && array_key_exists($last, $array) && is_null($array[$last])) {
            $result = true;
        }
        return $result;
    }

    public static function isChecked($value): bool
    {
        return in_array($value, ['yes', 'on', 1, '1', true]);
    }
}