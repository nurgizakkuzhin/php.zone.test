<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Регистрация</h1>
        <?php if (!empty($error)):?>
            <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
        <?php endif;?>
        <form action="/users/register" method="post">
            <label>Nickname <input type="text" name="nickname" value="<?php echo $_POST['nickname'] ?? ''; ?>"></label>
            <br><br>
            <label>Email <input type="text" name="email" value="<?php echo $_POST['email'] ?? ''; ?>"></label>
            <br><br>
            <label>Пароль <input type="password" name="password" value="<?php echo $_POST['password'] ?? ''; ?>"></label>
            <br><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>