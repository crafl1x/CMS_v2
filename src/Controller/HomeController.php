<?php

namespace App\Controller;

use Core\Database\Database;
use PDO;

class HomeController extends Controller {

    public function endpoint() {

        $search = "";

        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $data = $this->search($search);
        } else {

            $db = Database::connect();
            $conn = $db->query("SELECT * FROM posts WHERE public = 1");
            $data = $conn->fetchAll();
        }

        $this->render("home.html.twig", [
            "posts" => $data,
            "lastsearch" => $search
        ]);
    }

    private function search(string $search): array {
        $db = Database::connect();
        $conn = $db->prepare("SELECT * FROM posts WHERE public = 1 AND (name LIKE :search OR perex LIKE :search)");
        $conn->bindValue(":search","%".$search."%", PDO::PARAM_STR);
        $conn->execute();
        return $conn->fetchAll();
    }

}

?>