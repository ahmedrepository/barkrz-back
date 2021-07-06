<?php
/**
 * Created by PhpStorm.
 * User: Strong
 * Date: 2019-12-20
 * Time: 11:21 AM
 */

namespace App\Helper;


class Common
{
    public static function stdClass2Array($arr)
    {
        $result = array_map(function ($value) {
            return (array)$value;
        }, $arr);

        return $result;
    }

    public static function constant($key)
    {
        return config('constants.' . $key);
    }
}
