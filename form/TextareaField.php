<?php


namespace vajbon3\mvc\form;


use app\models\Model;

class TextareaField extends BaseField
{

    public function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);
    }

    protected function renderInput(): string
    {
        return sprintf('<textarea name="%s" class="form-control %s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? "is-invalid" :  "",
            $this->model->{$this->attribute}
        );
    }
}