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

        $data = array_merge($data,[
            'motd' => [ 'first' => $data['welcome'], 'seccond' => $data['sec_welcome'] ],
            'contacts' => [['info' => $data['email'], 'name' => "Email"],['info' => $data['phone'], 'name' => "Phone"]],
            'socials' => [['info' => strip_tags($data['instagram']), 'name' => "Instagram"],['info' => strip_tags($data['facebook']), 'name' => "Facebook"]]
        ]);

        $this->addToTwigGlobals([
            'webinfo' => $data
        ]);
    }

}

?>