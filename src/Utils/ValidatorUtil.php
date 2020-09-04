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
}