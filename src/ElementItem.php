<?php
namespace iPremium\Bitrix\iBlock;

class ElementItem
{
    //public $properties;

    public $atributes = [];

    public function __construct($element)
    {
        $this->atributes = $element;
    }

    public function __get($name)
    {
        //echo strtoupper($name);

        if (array_key_exists(strtoupper($name), $this->atributes))
        {
            return $this->atributes[strtoupper($name)];
        }
        else
        {
            return null;
        }

    }

    public function __set($name, $value)
    {
        $this->atributes[strtoupper($name)] = $value;
    }
}
