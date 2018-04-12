<?php

namespace App\Controllers;

use GUMP;
use App\Core\MainController;
use App\Models\User;
use App\Models\File;
use Intervention\Image\ImageManagerStatic as Image;

include('cryptPassword.php');

class Registration extends MainController
{
    protected function changeSaveImage($photo, $photoName)
    {
        $photo = Image::make($photo['tmp_name']);
        $photo->resize(100, null, function ($image) {
            $image->aspectRatio();
        });
        $photo->save("$this->uploads_dir/$photoName.jpg");

    }

    protected function validate($data)
    {
        $gump = new GUMP($lang = 'ru');
        $gump->validation_rules(
            array(
                'login' => 'required|alpha_numeric',
                'password' => 'required|max_len,100|min_len,3',
                'password2' => 'equalsfield,password',
                'name' => 'required|alpha_numeric',
                'age' => 'required|numeric',
                'photo' => 'required_file|extension,png;jpg;jpeg'
            )
        );

        $filters = array(
            'login' => 'trim|strtolower'
        );

        $post = $gump->filter($data, $filters);

        $gump->run($data);
        return [
            'post' => $post,
            'validate' => $gump
        ];
    }

    public function post()
    {
        if (empty($_POST)) {
            $this->view->twigLoad('registration', []);
        }
        $data = [];
        $result = $this->validate(array_merge($_POST, $_FILES));

        if (!empty($result['validate']->get_errors_array())) {
            $this->view->twigLoad('registration', [
                'error' => $result['validate']->get_errors_array()
            ]);
        }

        extract($result['post']);

        $maxId = File::all()->max('id') + 1;
        $photoName = md5($maxId);

        $user = User::where('login', '=', $login)->exists();
        if (!$user) {
            $password = hash256($password);
            $user = User::create([
                'login' => $login,
                'password' => $password,
                'name' => $name,
                'age' => $age,
                'description' => $description,
            ]);
            File::create([
                'fileName' => $photoName,
                'user_id' => $user->id,
            ]);
            if ($result == false) {
                $data = [
                    'error' => [
                        'login' => 'Такой логин уже существует!'
                    ]
                ];
                $this->view->twigLoad('template', $data);
            }
            try {
                $this->changeSaveImage($photo, $photoName);
            } catch (\Exception $e) {
                $user->delete();
                $this->view->twigLoad('registration', [
                    'error' => [
                        'photo' => 'Не удалось сохранить картинку'
                    ]
                ]);
            }
            $this->redirect('/registration');
        } else {
            $data = [
                'error' => [
                    'login' => 'Такой логин уже существует!'
                ]
            ];
            $this->view->twigLoad('registration', $data);
        }
    }

    public function index()
    {
        $data = [];
        $this->view->twigLoad('registration', $data);
    }
}
