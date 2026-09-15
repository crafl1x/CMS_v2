<?php

namespace App\AdminPanel;

class PostsController extends AdminController {

    public function endpoint(): void {
        
        $this->render("/admin/tablePosts.html.twig");
    }

    public function edit(): void {
        
    }

    public function delete(): void {
        
    }
}

?>
