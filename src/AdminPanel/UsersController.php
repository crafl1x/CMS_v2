<?php

namespace App\AdminPanel;

use App\Database\Database;
use App\Form\Form;
use App\Auth\Auth;
use PDO;
use PDOException;

class UsersController extends AdminController {

    public function endpoint() {

        Auth::requireLogin('admin');

        $db = Database::connect();
        $conn = $db->query("SELECT `id`,`email`,`role`,`timestamp` FROM `users`");
        $users = $conn->fetchAll();


        $this->render("/admin/table.html.twig", ["users" => $users]);
    }

    private function countAdmins(): int {
        $db = Database::connect();
        $conn = $db->query("SELECT COUNT(*) as count FROM `users` WHERE `role` = 'admin'");
        $count = $conn->fetch();

        if ($count) {
            return $count['count'];
        }

        return 0;
    }

    public function edit() {

        Auth::requireLogin('admin');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->editPOST();
        }

        $form = new Form();
        $form->get('id');
        $form->post('id');
        $id = $form->dispatch()['id'];

        if ($id == null) {
            header("location: /admin/users");
            exit;
        }

        $db = Database::connect();
        $conn = $db->prepare("SELECT `id`,`email`,`role`,`timestamp` FROM `users` WHERE `id` = :id");
        $conn->bindValue(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $user = $conn->fetch();

        $lockRole = true;
        if ($this->countAdmins() > 1 or Auth::getUserRole($id) != 'admin') {
            $lockRole = false;
        }

        $this->render("/admin/usersForm.html.twig", ["mode" => "edit", "form_name" => "Edit User", "item" => $user, "lockRole" => $lockRole ]);
    }

    private function editPOST(): void {
        $form = new Form();
        $form->post('id');
        $form->post('role');
        $req = $form->dispatch();

        if (!in_array($req['role'], Auth::getAvaibleRoles())) {
            return;
        }

        try {
            $db = Database::connect();
            $conn = $db->prepare("UPDATE `users` SET `role`=:role WHERE `id`=:id");
            $conn->bindValue(":id", $req['id'], PDO::PARAM_INT);
            $conn->bindValue(":role", $req['role'], PDO::PARAM_STR);
            $conn->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }

        
        header("location: /admin/users");
        exit;
        
    }

    public function delete() {

        Auth::requireLogin('admin');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->deletePOST();
        }

        $form = new Form();
        $form->get('id');
        $form->post('id');
        $id = $form->dispatch()['id'];

        if ($id == null) {
            header("location: /admin/users");
            exit;
        }

        $db = Database::connect();
        $conn = $db->prepare("SELECT `id`,`email`,`role`,`timestamp` FROM `users` WHERE `id` = :id");
        $conn->bindValue(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $user = $conn->fetch();

        $this->render("/admin/usersForm.html.twig", ["mode" => "delete", "form_name" => "Delete User", "item" => $user, "lockRole" => true ]);
    }

    private function deletePOST() {
        $form = new Form();
        $form->post('id');
        $id = $form->dispatch()['id'];

        try {
            $db = Database::connect();
            $conn = $db->prepare("DELETE FROM `users` WHERE `id`=:id");
            $conn->bindValue(":id", $id, PDO::PARAM_INT);
            $conn->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }

        
        header("location: /admin/users");
        exit;

}
}

?>