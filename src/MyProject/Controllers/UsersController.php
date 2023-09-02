<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserActivationService;
use MyProject\Models\Users\UserAuthService;
use MyProject\Services\EmailSender;
use MyProject\View\View;

class UsersController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::singUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('/users/singUp.php',
                ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);

                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }

        $this->view->renderHtml('/users/singUp.php');

    }

    public function activate(int $userId, string $activationCode)
    {
        $user = User::getById($userId);
        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate();
            echo 'OK!';
        }
    }

    public function logIn()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UserAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/login.php');
    }

    public function logOut()
    {
       setcookie('token', '', -1, '/', '', false, true);
       header('Location: /');
    }
}