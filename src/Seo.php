<?php
namespace iPremium\Bitrix\Seo;

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

    public static function requestUri()
    {
        return static::$_meta->requestUri();
    }

    public static function __callStatic($method, $params)
    {
        return static::$_meta->$method(...$params);
    }

}
