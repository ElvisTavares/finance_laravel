<?php

class Link {
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl(){
        return url($this->url);
    }
}