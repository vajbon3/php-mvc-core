<?php


namespace vajbon3\mvc\form;


use app\models\Model;

abstract class BaseField
{
    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     * @param string $type
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString(): string
    {
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ',  $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute));
    }

    abstract protected function renderInput(): string;
}