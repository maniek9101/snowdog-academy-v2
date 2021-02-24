<?php

namespace Snowdog\Academy\Controller;

use Snowdog\Academy\Model\UserManager;

class Login
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function index(): void
    {
        require __DIR__ . '/../view/login/index.phtml';
    }

    public function login(): void
    {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $user = $this->userManager->getByLogin($login);
        if ($user && $this->userManager->verifyPassword($user, $password)) {
            $_SESSION['login'] = $login;
            $_SESSION['flash'] = 'Hello ' . $user->getLogin() . '!';
            header('Location: /');
            return;
        }

        $_SESSION['flash'] = 'Incorrect login or password';
        header('Location: /login');
    }

    public function logout(): void
    {
        if (isset($_SESSION['login'])) {
            unset($_SESSION['login'], $_SESSION['is_admin']);
            $_SESSION['flash'] = 'Logged out successfully';
        }

        header('Location: /login');
    }
}
