<?php

namespace Sim\Form\Utils;

class LocalUtil
{
    /**
     * @var array
     */
    private static $persian_decimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');

    /**
     * @var array
     */
    private static $arabic_decimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');

    /**
     * @var array
     */
    private static $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * @var array
     */
    private static $arabic_numbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    /**
     * @var array
     */
    private static $english_numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @var array
     */
    private static $persian_special_characters = ['ا', 'گ', 'چ', 'پ', 'ژ', 'ه', 'ی'];

    /**
     * @var array
     */
    private static $arabic_special_characters = ['أ', 'ك', 'ج', 'ب', 'ز', 'ة', 'ي'];

    /**
     * convert arabic and english numbers and arabic specific
     * characters to persian numbers and specific characters
     *
     * @param $str
     * @return array|mixed
     */
    public static function toPersian($str)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toPersian($v);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace(self::$english_numbers, self::$persian_numbers, $str);
            $str = str_replace(self::$arabic_numbers, self::$persian_numbers, $str);
            $str = str_replace(self::$arabic_decimal, self::$persian_numbers, $str);
            $str = str_replace(self::$arabic_special_characters, self::$persian_special_characters, $str);
        }

        return $str;
    }

    /**
     * convert persian and english numbers and persian specific
     * characters to arabic numbers and specific characters
     *
     * @param $str
     * @return array|mixed
     */
    public static function toArabic($str)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toArabic($v);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace(self::$english_numbers, self::$arabic_numbers, $str);
            $str = str_replace(self::$persian_numbers, self::$arabic_numbers, $str);
            $str = str_replace(self::$persian_decimal, self::$arabic_numbers, $str);
            $str = str_replace(self::$persian_special_characters, self::$arabic_special_characters, $str);
        }

        return $str;
    }

    /**
     * Convert numbers from arabic and persian to english numbers
     *
     * @param $str
     * @return array|mixed
     */
    public static function toEnglish($str)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toEnglish($v);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace(self::$arabic_numbers, self::$english_numbers, $str);
            $str = str_replace(self::$persian_numbers, self::$english_numbers, $str);
            $str = str_replace(self::$persian_decimal, self::$english_numbers, $str);
            $str = str_replace(self::$arabic_decimal, self::$english_numbers, $str);
        }

        return $str;
    }
}