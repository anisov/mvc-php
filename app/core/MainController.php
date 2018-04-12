<?php

namespace App\Core;

class MainController
{
    protected $view;
    protected $uploads_dir;

    public function __construct()
    {
        $this->view = new View();
        $this->uploads_dir = (include 'settings.php')['uploads_dir'];

    }

    public function redirect(String $filename)
    {
        header('Location:' . $filename, true, 301);
        die();
    }

}
