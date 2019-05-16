<?php
namespace iPremium\Bitrix\iBlock;

use iPremium\Bitrix\iBlock\ElementItem as Item;

class Element
{
    /*
    use this param true if you want write in lovercase params
    and script auto transform it to BITRIX_CAPS_STYLE
     */
    protected $lovercase = false;

    protected $properties;

    protected $select;

    protected $filter;

    protected $iblockId;

    protected $order = [
        "SORT"=>"ASC"
    ];

    protected $defalutSelect = [
        "ID",
        "NAME",
        "DETAIL_PICTURE",
        "DETAIL_PAGE_URL"
    ];

    protected $returnArray = false;

    public function __construct($id, $return)
    {
        $this->iblockId = $id;
        $this->returnArray = $return;
        //to do use global settings in iBLock::instanse()->elementLoverCase();
    }

    public function lovercase($param)
    {
        $this->lovercase = $param;
    }

    public function select(...$params)
    {
        if ($this->lovercase)
        {
            foreach ($params as &$param) {
                $param = strtoupper($param);
            }

            $this->select = $params;
        }
        else
        {
            $this->select = $params;
        }

        return $this;
    }

    public static function iblock($id, $return = false)
    {
        return new static($id, $return);
    }

    public function filter($params)
    {
        if (!in_array($params, ['iblock_id', 'IBLOCK_ID']))
        {
            if ($this->iblockId != null)
            {
                $params = array_merge($params, ['IBLOCK_ID' => $this->iblockId]);
            }
        }

        if ($this->lovercase)
        {
            foreach ($params as &$param) {
                $param = strtoupper($param);
            }

            $this->filter = $params;
        }
        else
        {
            $this->filter = $params;
        }

        return $this;
    }

    public function order($params)
    {
        $this->order = $params;
    }

    public function get($count = 9999, $method = null)
    {
        $elements = [];

        if ( \CModule::IncludeModule("iblock"))
        {
            $select = ($this->select == null) ? $this->select : $this->defalutSelect;

        	$res = \CIBlockElement::GetList($this->order, $this->filter, false, [ "nPageSize" => $count ], $select);

        	while($ob = $res->GetNextElement())
        	{
    			$element = $ob->GetFields();
    			$element["PROPERTIES"] = $ob->GetProperties();

                if ( is_callable($method) )
                {
                    call_user_func_array($method, [ &$element ]);
                }

                if ($this->returnArray) {
                    if ($count == 1) {
                        return $elements = $element;
                    }

                    $elements[] = $element;
                }
                else
                {
                    if ($count == 1) {
                        $elements = new Item($element);
                    }
                }

        	}

            return $elements;
        }
        else
        {
            return false;
        }
    }

    public function first($method = null)
    {
        return $this->get(1, $method);
    }

}
