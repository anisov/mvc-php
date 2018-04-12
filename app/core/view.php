<?php

namespace App\Core;

class View
{
    public function __construct($data = [])
    {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
        $this->twig = new \Twig_Environment($this->loader);
    }

    public function render(String $filename, array $data)
    {
        $templateName = TEMPLATE_DIR . DIRECTORY_SEPARATOR . $filename . ".php";
        extract($data);
        require $templateName;
        die();
    }

    public function twigLoad(String $filename, array $data)
    {
        echo $this->twig->render("$filename" . ".twig", $data);
        die();
    }
}

