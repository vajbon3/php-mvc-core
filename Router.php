<?php

namespace vajbon3\mvc;

use app\controllers\Controller;
use vajbon3\mvc\exceptions\NotFoundException;

class Router
{
    protected array $routes = [];

    public function get($path, $callback) {
        $this->routes["get"][$path] = $callback;
    }

    public function post(string $string, $param)
    {
        $this->routes["post"][$string] = $param;
    }

    public function resolve()
    {
        $path = Application::$app->request->getPath();
        $method = Application::$app->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if(!$callback) {
            return Application::$app->view->renderView("/_error", [
                "exception" => new NotFoundException(),
            ]);
        }

        if(is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if(is_array($callback)) {
            /** @var Controller $controller */
            // set controller in global app
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach($controller->middleware as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, Application::$app->request, Application::$app->response);
    }
}