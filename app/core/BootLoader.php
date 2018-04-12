<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class BootLoader
{
    private $config;

    public function __construct()
    {
        $this->initConfig();
        $this->initOrm();

    }

    public function initConfig()
    {
        $filename = realpath(__DIR__ . '/config.php');
        if (!$filename) {
            echo 'Файл конфига не существует';
            exit();
        }
        $this->config = include $filename;
        $this->config = $this->config['db'];
    }

    public function initOrm()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $this->config['host'],
            'database' => $this->config['dbname'],
            'username' => $this->config['user'],
            'password' => $this->config['$password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}