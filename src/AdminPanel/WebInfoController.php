<?php

namespace App\AdminPanel;

use App\Auth\Auth;
use App\Database\Database;
use App\Form\Form;

class WebInfoController extends AdminController {

    public function endpoint() {

        Auth::requireLogin('admin');

        $db = Database::connect();
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $form = new Form();
            $form->post('websitename');
            $form->post('welcome');
            $form->post('sec_welcome');
            $form->post('phone');
            $form->post('email');
            $form->post('instagram');
            $form->post('facebook');
            $data = $form->dispatch();

            $conn = $db->prepare("INSERT INTO `webinfo` (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = :value");

            foreach ($data as $key => $value) {
                $conn->execute(['key' => $key, 'value' => $value]);
            }

            $success = true;

            
        }
        $conn = $db->query("SELECT `key`,`value` FROM `webinfo`");
        $data = $conn->fetchAll();
        $data = array_column($data, 'value', 'key');

        

        $this->render("/admin/webinfo.html.twig", ['webinfo' => $data, 'success' => $success]);   
    }
}

?>
