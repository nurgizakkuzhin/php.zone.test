<?php

try {

    require __DIR__ . '/vendor/autoload.php';


    $route = $_SERVER['REQUEST_URI'];
    $routes = require __DIR__ . '/src/routes.php';

    $isRouteFound = false;

    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
       throw new \MyProject\Exceptions\NotFoundException();
    }
    unset($matches[0]);
    [$controller, $action] = $controllerAndAction;
    $ctrl = new $controller;
    $ctrl->$action(...$matches);
} catch (\MyProject\Exceptions\DbException $e) {
    $view = new MyProject\View\View(__DIR__ . '/src/templates/errors');
    $view->renderHtml('500.php', ['errors' => $e->getMessage()], 500);
} catch ( \MyProject\Exceptions\NotFoundException $e) {
    $view = new MyProject\View\View(__DIR__ . '/src/templates/errors');
    $view->renderHtml('404.php', ['errors' => $e->getMessage()], 404);
} catch (\MyProject\Exceptions\UnauthorizedException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/src/templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyProject\Exceptions\ForbiddenException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/src/templates/errors');
    $view->renderHtml('403.php', ['error' => $e ->getMessage()], 403);
}
