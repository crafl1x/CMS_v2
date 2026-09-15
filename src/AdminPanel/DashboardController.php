<?php

namespace App\AdminPanel;

use \App\Controller\Controller;

class DashboardController extends Controller {

    public function endpoint() {

        

        $this->render("base.html.twig");
    }

}

?>