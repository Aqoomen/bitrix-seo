<?php
namespace iPremium\Bitrix\Seo;

use iPremium\Bitrix\Seo\Meta;

class Seo
{
    protected static $_meta = null;

    public static function hasFilter($path)
    {
        $path = explode("/", $path );

        if (in_array("filter", $path)) {
            return true;
        } else {
            return false;
        }
    }

    public static function meta()
    {
        if (is_null(static::$_meta))
        {
            static::$_meta = new Meta;
        }

        return static::$_meta;
    }

    public static function setMeta(Meta $meta)
    {
        static::$_meta = $meta;
    }

    public static function addString(Asset $asset, $string)
    {
        return $asset->addString($string);
    }


    public static function __callStatic($method, $params)
    {
        if (is_null(static::$_meta))
        {
            static::$_meta = new Meta;
        }
        //var_dump(static::$_meta);
        return call_user_func_array([static::$_meta, $method], $params);
    }

}
