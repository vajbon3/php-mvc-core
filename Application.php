<?php

namespace vajbon3\mvc;

use app\controllers\Controller;
use vajbon3\mvc\db\Database;

class Application
{
    public static Application $app;

    public string $layout = "main";
    public string $userClass;
    public string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?Controller $controller = null;
    public ?UserModel $user;
    public View $view;

    public function __construct($rootPath, array $config)
    {
        self::$app = $this;

        $this->userClass = $config["userClass"];
        $this->ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router();
        $this->view = new View();
        $this->db = new Database($config["db"]);

        $primaryValue = $this->session->get("user");
        if($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }

    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch(\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView("_error", [
                "exception" => $e
            ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set("user", $primaryValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove("user");
    }
}