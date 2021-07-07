<?php


namespace vajbon3\mvc\middleware;


use vajbon3\mvc\Application;
use vajbon3\mvc\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions;
    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(Application::isGuest()) {
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}