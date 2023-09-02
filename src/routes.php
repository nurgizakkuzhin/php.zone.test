<?php

return [
    '~^/articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^/articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^/articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'delete'],
    '~^/articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^/users/register$~' => [\MyProject\Controllers\UsersController::class, 'signUp'],
    '~^/users/login$~' => [\MyProject\Controllers\UsersController::class, 'logIn'],
    '~^/users/logout$~' => [\MyProject\Controllers\UsersController::class, 'logOut'],
    '~^/users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^/hello/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^/bye/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayBye'],
    '~^/$~' => [\MyProject\Controllers\MainController::class, 'main'],
];