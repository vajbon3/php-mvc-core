<?php


namespace vajbon3\mvc\form;


use app\models\Model;

class Form
{
    public static function begin($action, $method) {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end() {
        echo "</form>";
    }

    public function inputField(Model $model, string $attribute,
                               string $type = "text") {
        return new InputField($model, $attribute, $type);
    }

    public function textareaField(Model $model, string $attribute) {
        return new TextareaField($model,$attribute);
    }

    public function button($text) {
        return new Button($text);
    }
}