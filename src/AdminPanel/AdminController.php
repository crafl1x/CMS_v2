<?php

namespace App\AdminPanel;

use App\Auth\Auth;
use App\Controller\Controller;
use App\Database\Database;
use PDO;


class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requireLogin('editor');

        $db = Database::connect();
        $conn = $db->prepare("SELECT `email`,`role` FROM `users` WHERE `id`=:id");
        $conn->bindValue(":id", Auth::getUserID(), PDO::PARAM_INT);
        $conn->execute();

        $data = $conn->fetch();

        $this->addToTwigGlobals([
            "account" => $data
        ]);
    }

}

?>