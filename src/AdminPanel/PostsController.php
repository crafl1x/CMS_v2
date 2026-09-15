<?php

namespace App\AdminPanel;

use \App\Form\Form;
use \App\Database\Database;
use \App\Auth\Auth;
use PDO;
use PDOException;

class PostsController extends AdminController {

    private $maxLengths = [
        "name" => 255,
        "author" => 255,
        "perex" => 255
    ];

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

    public function validateData(array $formData): array {

        $error = [];   
    
        if (strlen($formData['name']) > $this->maxLengths['name']) {
            $error['nameLength'] = true;
        }


        if (strlen($formData['author']) > $this->maxLengths['author']) {
            $error['authorLength'] = true;
        }

        if (strlen($formData['perex']) > $this->maxLengths['author']) {
            $error['perexLength'] = true;
        }


    
        return $error;
    }

    public function new(): void {

        $formData['author'] = Auth::getEmailbyID(Auth::getUserID());

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $form = new Form();
            $form->post('name');
            $form->post('author');
            $form->post('perex');
            $form->post('content');
            $form->post('status', 'private');
            $formData = $form->dispatch();

            if ($formData['status'] == 'on') {
                $formData['status'] = 'public';
            }

            var_dump($formData);

            $error = $this->validateData($formData);
            
            if ($error === []) {
                try {
                $db = Database::connect();
                $conn = $db->prepare("INSERT INTO `posts` (`name`, `author`, `status`, `perex`, `content`) VALUES (:name, :author, :status, :perex, :content)");
                $conn->bindValue(":name", $formData['name'], PDO::PARAM_STR);
                $conn->bindValue(":author", $formData['author'], PDO::PARAM_STR);
                $conn->bindValue(":status", $formData['status'], PDO::PARAM_STR);
                $conn->bindValue(":perex", $formData['perex'], PDO::PARAM_STR);
                $conn->bindValue(":content", $formData['content'], PDO::PARAM_STR);
                $conn->execute();

                header("location: /admin/posts");
                exit;
            } catch (PDOException $e) {
               throw new PDOException($e->getMessage());
            }
            }
        }

        $this->render("/admin/postsForm.html.twig", ['mode' => "new", "post" => $formData]);    
    }

    public function edit(): void {

        

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $form = new Form();
            $form->post('id');
            $form->post('name');
            $form->post('author');
            $form->post('perex');
            $form->post('content');
            $form->post('status', 'private');
            $formData = $form->dispatch();

            if ($formData['status'] == 'on') {
                $formData['status'] = 'public';
            }

            $error = $this->validateData($formData);

            if ($error === []) {
                try {
                    $db = Database::connect();
                    $conn = $db->prepare("UPDATE `posts` SET `name`=:name, `author`=:author, `status`=:status, `perex`=:perex, `content`=:content WHERE `id`=:id");
                    $conn->bindValue(":id", $formData['id'], PDO::PARAM_INT);
                    $conn->bindValue(":name", $formData['name'], PDO::PARAM_STR);
                    $conn->bindValue(":author", $formData['author'], PDO::PARAM_STR);
                    $conn->bindValue(":status", $formData['status'], PDO::PARAM_STR);
                    $conn->bindValue(":perex", $formData['perex'], PDO::PARAM_STR);
                    $conn->bindValue(":content", $formData['content'], PDO::PARAM_STR);
                    $conn->execute();

                    header("location: /admin/posts");
                    exit;
                } catch (PDOException $e) {
                    throw new PDOException($e->getMessage());
                }

                
            }


        } else {
            $form = new Form();
            $form->get('id');
            $id = $form->dispatch()['id'];

            $db = Database::connect();
            $conn = $db->prepare("SELECT `id`,`name`,`author`,`timestamp`,`status` FROM `posts` WHERE `id` = :id");
            $conn->bindValue(":id", $id, PDO::PARAM_INT);
            $conn->execute();

            $formData = $conn->fetch();
        }

        $this->render("/admin/postsForm.html.twig", ['mode' => "edit", "post" => $formData]);    
    }

    public function delete(): void {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $form = new Form();
            $form->post('id');
            $id = $form->dispatch()['id'];

            $db = Database::connect();
            $conn = $db->prepare("DELETE FROM `posts` WHERE `id`=:id");
            $conn->bindValue(":id", $id, PDO::PARAM_INT);
            $conn->execute();

            header("location: /admin/posts");
            exit;
        }

        $form = new Form();
        $form->get('id');
        $id = $form->dispatch()['id'];

        $db = Database::connect();
        $conn = $db->prepare("SELECT `id`,`name`,`author`,`timestamp`,`status` FROM `posts` WHERE `id` = :id");
        $conn->bindValue(":id", $id, PDO::PARAM_INT);
        $conn->execute();

        $formData = $conn->fetch();

        $this->render("/admin/postsForm.html.twig", ['mode' => "delete", "post" => $formData]);    
    }
}

?>
