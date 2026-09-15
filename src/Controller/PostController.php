<?php

namespace App\Controller;

use App\Database\Database;
use PDO;

class PostController extends Controller {


    public function endpoint() {

        $id = (int)$_GET['id'] ?? 0;

        $db = Database::connect();

        $conn = $db->prepare("SELECT * FROM posts WHERE `status` = 'public' AND id = :id");
        $conn->bindValue(":id", $id, PDO::PARAM_INT);
        $conn->execute();

        $data = $conn->fetch();

        if ($data == false) {
            http_response_code(404);
            echo "404 Not Found";
        }

        $this->render("post.html.twig", $data);
    }
}

?>