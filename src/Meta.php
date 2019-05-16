<?php
namespace iPremium\Bitrix\Seo;

class Meta
{
    protected $title;
    protected $keywords;
    protected $metatext;
    protected $meaturl;
    protected $description;
    protected $canonical;

    public function __construct()
    {

    }

    public function get($name)
    {
        return $this->$name;
    }

    public function set($name, $value)
    {
        $this->$name = $value;
    }

    public function setTitle($title)
    {
        $this->set("title", $title);
    }

    public function prepairUri($url)
    {
        if (($index = strpos($url, "/index.php")) == true)
        {
            $url = str_replace($index, '/', $url);
        }
        else
        {
            return $url;
        }

    }

    public function requestUri($full = true)
    {
        return ($full)  ?
                        $this->prepairUri($_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                        : $this->prepairUri($_SERVER['REQUEST_URI']);

    }

    protected function takeCanonical($string = null)
    {
        return (is_null($string))  ? $this->prepareStringUri($this->requestUri()) : $string ;
    }

    public function prepareStringUri($uri)
    {
        $uriList = parse_url($uri);

        unset($uriList['query']);

        return $uriList['scheme'] . '://' . $uriList['host'] . $uriList['path'];

    }

    public function canonical($string = null)
    {
        $this->canonical = $this->takeCanonical($string);
    }

    public function htmlCanonical()
    {
        return '<link rel="canonical" href=" ' . $this->get('canonical') .  ' "  />';
    }

    public function prepareNext()
    {
        return $this->get('canonical') . '?PAGEN_1=' . ($this->getPage() + 1);
    }

    public function preparePrev()
    {
        return $this->get('canonical') . '?PAGEN_1=' . ($this->getPage() - 1);
    }

    public function htmlNext()
    {
        if ($this->hasPagination())
        {
            return '<link rel="canonical" href=" ' . $this->prepareNext() .   ' "  />';
        }

        return null;
    }

    /*
    return current page in pagination bitrix
     */
    public function getPage()
    {
        if ($this->hasPagination())
        {
            return intVal($_GET['PAGEN_1']);
        }

        return null;
    }

    public function hasPagination()
    {
        if (array_key_exists('PAGEN_1', $_GET))
        {
            return true;
        }

        return false;
    }

    public function htmlPrev()
    {
        if ($this->hasPagination())
        {
            return '<link rel="canonical" href=" ' . $this->preparePrev() .   ' "  />';
        }

        return null;
    }

}
