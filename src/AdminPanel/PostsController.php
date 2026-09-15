<?php

namespace App\AdminPanel;

use \App\Form\Form;
use \App\Database\Database;
use PDO;

class PostsController extends AdminController {

    public function endpoint(): void {

        $form = new Form();
        $form->get('search', '');
        $form->get('author', '');
        $form->get('status', '');

        $search = $form->dispatch();

        $db = Database::connect();
        $conn = $db->prepare("SELECT `id`,`name`,`author`,`timestamp`,`status` FROM `posts` WHERE `name` LIKE :search AND `author` LIKE :author AND `status` LIKE :status");
        $conn->bindValue(":search","%".$search['search']."%", PDO::PARAM_STR);
        $conn->bindValue(":author","%".$search['author']."%", PDO::PARAM_STR);
        $conn->bindValue(":status","%".$search['status']."%", PDO::PARAM_STR);
        $conn->execute();
        $posts = $conn->fetchAll();
        
        $this->render("/admin/tablePosts.html.twig", ["posts" => $posts, "lastSearch" => $search]);
    }

    public function edit(): void {
        
    }

    public function delete(): void {
        
    }
}

?>
