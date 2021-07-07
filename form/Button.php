<?php


namespace vajbon3\mvc\form;


class Button
{

    public string $text;

    /**
     * Button constructor.
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "<button type='submit' class='btn btn-primary'>$this->text</button>";
    }
}