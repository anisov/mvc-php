<?php
require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Core\BootLoader;

new BootLoader();

require_once __DIR__ . "/userMigrations.php";
require_once __DIR__ . "/fileMigrations.php";


new userMigrations();
new fileMigrations();
