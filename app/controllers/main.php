<?php

namespace App\Controllers;

use App\Core\MainController;
use App\Models\User;


include('cryptPassword.php');

class Main extends MainController
{
    public function index()
    {
        if ($_POST) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $currentUser = User::where('login', '=', $login)->first();
            if ($currentUser) {
                $ownPassword = $currentUser->password;
                $currentPassword = hash256($password);
                if ($ownPassword == $currentPassword) {
                    $_SESSION["user"] = $currentUser['name'];
                } else {
                    $data['error'] = 'Пароль неверный';
                    $this->view->twigLoad('index', $data);
                }
            } else {
                $data['error'] = 'Такого логина не существует';
                $this->view->twigLoad('index', $data);
            }
        }
        $data = [$_SESSION["user"]];
        $this->view->twigLoad('index', $data);
    }
}