<?php

namespace App\Controllers;

use App\Core\MainController;
use App\Models\File;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;

class UserList extends MainController
{
    protected function changeSaveImage($photo, $photoName)
    {
        $photo = Image::make($photo['tmp_name']);
        $photo->resize(100, null, function ($image) {
            $image->aspectRatio();
        });
        $photo->save("$this->uploads_dir/$photoName.jpg");

    }

    public function post()
    {
        $data = [];
        if ($_POST) {
            $id = $_POST['id'];
            $photo = empty($_FILES['photo']) ? null : $_FILES['photo'];
            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            $img_exts = ['jpeg', 'jpg', 'png'];

            if (!$photo) {
                $data['error']['error-photo'] = 'Загрузите картинку';
            } else {
                if ($photo['error'] !== UPLOAD_ERR_OK) {
                    $data['error']['error-photo'] = 'Ошибка загрузки';
                }
                if (!in_array($ext, $img_exts)) {
                    $data['error']['error-photo'] = 'Неверный формат картинки';
                }
            }

            if ($data['error']) {
                $this->view->twigLoad('list', $data);
            }

            $maxId = File::all()->max('id') + 1;
            $photoName = md5($maxId);

            $user = User::find($id);
            if ($user) {
                $file = File::create([
                    'fileName' => $photoName,
                    'user_id' => $user->id,
                ]);
                try {
                    $this->changeSaveImage($photo, $photoName);
                } catch (\Exception $e) {
                    $file->delete();
                    $this->view->twigLoad('registration', [
                        'error' => [
                            'photo' => 'Не удалось сохранить картинку'
                        ]
                    ]);
                }
            } else {
                $data['error']['error-user'] = 'Такого пользователя не существует';
            }
            $this->redirect('/userlist');
        }
        $this->view->twigLoad('list', $data);
    }

    public function delete()
    {
        if ($_GET['id']) {
            $id = $_GET['id'];
            $currentUser = User::with('files')->where('id', '=', $id)->first();
            if ($currentUser) {
                $files = $currentUser->files;
                foreach ($files as $file) {
                    $photoPas = $this->uploads_dir . '/' . $file->fileName . '.jpg';
                    if (file_exists($photoPas)) {
                        unlink($photoPas);
                    } else {
                        $data['error']['error-delete'] = 'Ошибка удаления фотографии';
                    }
                }
                $currentUser->delete();
                $this->redirect('/userlist');
            } else {
                $data['error']['error-user-delete'] = 'Такого пользователя нет';
                $this->view->twigLoad('list', $data);
            }
        }
    }

    public function index()
    {
        $data = [];
        $_SESSION["user"];
        if ($_SESSION["user"]) {
            $data['uploads_dir'] = $this->uploads_dir;
            $sort = $_GET['sort'];
            $allUsers = User::with('files')->get();
            $allUsers->each(function ($user) {
                if ($user->age >= 18) {
                    $user->status = 'Совершеннолетний';
                } else {
                    $user->status = 'Несовершеннолетний';
                }
            });
            if ($sort == 'desc') {
                $allUsers = $allUsers->sortByDesc('age');
            } else {
                $allUsers = $allUsers->sortBy('age');
            }
            $data['users'] = $allUsers;
            $this->view->twigLoad('list', $data);
        } else {
            $this->redirect('/');
        }
    }
}
