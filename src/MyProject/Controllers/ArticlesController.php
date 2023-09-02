<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserAuthService;
use MyProject\Services\Db;
use MyProject\View\View;

class ArticlesController extends AbstractController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Db::getInstance();
    }

    public function view(int $articleId)
    {
        $article = Article::getById($articleId);
        $reflector = new \ReflectionObject($article);
        $properties = $reflector->getProperties();
        $propertiesNames = [];
        foreach ($properties as $property) {
            $propertiesNames[] = $property->name;
        }

        if ($article === []) {
            throw new NotFoundException();
        }

        $this->view->renderHtml('/articles/view.php', ['article' => $article]);
    }

    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для редактирования статьи нужно обладать правами администратора', 403);
        }

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('/articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function delete(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $article->delete();
        $this->view->renderHtml('articles/delete.php');


    }

    public function add()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if ($this->user->isAdmin()) {
            throw new ForbiddenException('Доступ запрещен!', 403);
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('/articles/add.php', ['error' => $e->getMessage()]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('/articles/add.php');

    }

}