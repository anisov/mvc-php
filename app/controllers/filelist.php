<?php

namespace App\Controllers;

use App\Core\MainController;
use App\Models\File;

class FileList extends MainController
{
    public function delete()
    {
        if ($_GET['id']) {
            $id = $_GET['id'];
            $file = File::find($id);
            $photoPas = $this->uploads_dir . '/' . $file['fileName'] . '.jpg';
            if ($file->delete()) {
                if (file_exists($photoPas)) {
                    unlink($photoPas);
                } else {
                    $data['error'] = 'Ошибка удаления';
                }
                $this->redirect('/filelist');
            } else {
                $data['error'] = 'Ошибка удаления';
            }
        }
    }

    public function index()
    {
        $data = [];
        $_SESSION["user"];
        if ($_SESSION["user"]) {
            $files = File::all();
            $data['files'] = $files;
            $data['uploads_dir'] = $this->uploads_dir;

            $this->view->twigLoad('filelist', $data);
        } else {
            $this->redirect('/');
        }
    }
}
