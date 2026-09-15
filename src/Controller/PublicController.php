<?php

namespace App\Controller;

use App\Database\Database;


class PublicController extends Controller {

    public function __construct() {
        parent::__construct();
        
        $db = Database::connect();
        $conn = $db->query("SELECT * FROM `webinfo`");
        $data = $conn->fetchAll();
        $data = array_column($data, 'value', 'key');

        $this->addToTwigGlobals([
            'webinfo' => $data
        ]);
    }

}

?>