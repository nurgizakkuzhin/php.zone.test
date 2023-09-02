<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой блог</title>
    <link rel="stylesheet" href="/src/www/style.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Мой блог
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
            <?php if (!empty($user) != null ): ?>
                Привет, <?php echo $user->getNickname(); ?> | <a href="/users/logout">Выйти</a>
            <?php else: ?>
                <a href="/users/login">Войти</a> | <a href="/users/register">Зарегистрироваться</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>