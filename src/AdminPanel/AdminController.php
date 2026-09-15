<?php

namespace App\AdminPanel;

use App\Auth\Auth;
use App\Controller\Controller;


class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requireLogin('editor');
    }

}

?>