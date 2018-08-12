<?php

class LinkResponsive
{
    private $colMd;
    private $colSm;
    private $colMdOffset;
    private $colSmOffset;
    private $linkClass;
    private $iconClass;
    private $link;
    private $text;

    public function __construct($url, $linkClass, $iconClass, $text = '')
    {
        $this->link = new Link($url);
        $this->linkClass = $linkClass;
        $this->iconClass = $iconClass;
        $this->text = $text;
    }

    public function html($count)
    {
        if ($count == 1) {
            $this->colMd = 4;
            $this->colSm = 4;
            $this->colMdOffset = 8;
            $this->colSmOffset = 8;
        } elseif($count == 2){
            $this->colMd = 6;
            $this->colSm = 6;
        } elseif ($count == 3) {
            $this->colMd = 4;
            $this->colSm = 4;
        }
        $divClassArray = [];
        if (isset($this->colSm)) {
            $divClassArray[] = 'col-sm-' . $this->colSm;
        }
        if (isset($this->colSmOffset)) {
            $divClassArray[] = 'offset-sm-' . $this->colSmOffset;
        }
        if (isset($this->colMd)) {
            $divClassArray[] = 'col-md-' . $this->colMd;
        }
        if (isset($this->colMdOffset)) {
            $divClassArray[] = 'offset-md-' . $this->colMdOffset;
        }
        $iconHTML = (new TagTemplate('i', ['class' => $this->iconClass]))->html().' '.$this->text;
        $linkHTML = (new TagTemplate('a', ['class' => $this->linkClass, 'href' => $this->link->getUrl()], $iconHTML))->html();
        return (new TagTemplate('div', ['class' => implode(' ', $divClassArray)], $linkHTML))->html();
    }
}