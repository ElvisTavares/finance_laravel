<?php

class TagTemplate
{
    const AUTO_CLOSE_TAGS = ['input'];
    private $tag;
    private $attributes;
    private $content;

    /**
     * TagTemplate constructor.
     * @param $tag
     * @param array $attributes
     * @param string $content
     */
    public function __construct($tag, $attributes = [], $content = '')
    {
        $this->tag = $tag;
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * Function to get html from parameters
     *
     * @return string
     */
    public function html()
    {
        $attributes = [];
        foreach ($this->attributes as $name => $value) {
            $attributes[] = $name . '="' . $value . '"';
        }
        if (in_array($this->tag, TagTemplate::AUTO_CLOSE_TAGS)) {
            return "<" . $this->tag . " " . implode(' ', $attributes) . "/>" . $this->content;
        } else {
            return "<" . $this->tag . " " . implode(' ', $attributes) . ">" . $this->content . "</" . $this->tag . ">";
        }
    }
}