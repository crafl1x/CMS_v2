<?php

namespace App\AdminPanel;

class DashboardController extends AdminController {

    public function endpoint() {

        

        $this->render("/admin/base.html.twig");
    }

}

?>