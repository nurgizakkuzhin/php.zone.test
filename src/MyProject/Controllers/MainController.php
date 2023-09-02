<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Models\Users\UserAuthService;
use MyProject\Services\Db;
use MyProject\View\View;

class MainController extends AbstractController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Db::getInstance();
    }

    public function main()
    {
        $articles = Article::findAll();
        $this->view->renderHtml(
            '/main/main.php',
            ['articles' => $articles, 'title' => 'Мой блог', 'user' => UserAuthService::getUserByToken()]);

    }

    public function sayHello(string $name)
    {
        $this->view->renderHtml('/main/hello.php', ['name' => $name, 'title' => 'Моя страница']);
    }

    public function sayBye(string $name)
    {
        echo 'пока ' . $name;
    }

}