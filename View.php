<?php


namespace vajbon3\mvc;


class View
{
    public string $title = "";

    public function renderView(string $view, $params = [])
    {
        $viewContent = $this->viewContent($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    private function layoutContent()
    {
        $layout = Application::$app->controller->layout ?? Application::$app->layout;
        ob_start();
        include_once Application::$app->ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function viewContent($view, $params = []) {
        // declare passed parameters in local scope
        foreach($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$app->ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}