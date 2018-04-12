<?php
require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Core\BootLoader;

new BootLoader();

use App\Models\User;
use App\Models\File;
use Faker\Factory;

function hash256($password)
{
    $password = hash('sha256', $password);
    return $password;
}

$factory = Factory::create();
for ($i = 0; $i < 10; $i++) {
    $user = new User();
    $user->login = $factory->name();
    $user->password = hash256($factory->password(33)); //как быть с хэшом?
    $user->name = $factory->name();
    $user->age = $factory->numberBetween(1 - 99);
    $user->description = $factory->text;
    $user->save();

    $file = new File();
    $file->fileName = hash256($file->id);
    $file->user_id = $user->id;
    $file->save();
}
