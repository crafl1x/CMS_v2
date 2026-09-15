<?php

namespace App\AdminPanel;

use App\Database\Database;

class UsersController extends AdminController {

    public function endpoint() {

        $db = Database::connect();
        $conn = $db->query("SELECT `id`,`email`,`role`,`timestamp` FROM users");
        $users = $conn->fetchAll();

        var_dump($users);


        $this->render("/admin/table.html.twig", $users);
    }

    public function edit() {}

    public function delete() {}
}

?>