<?php

namespace App\Form;

class Form {

    private $get_inputs = [];
    private $post_inputs = [];

    public function get(string $key, $default = null): void {
        if (isset($_GET[$key])) {
            $this->get_inputs[$key] = $_GET[$key];
        } else {
            $this->get_inputs[$key] = $default;
        }
    }

    public function post(string $key, $default = null): void {
        if (isset($_POST[$key])) {
            $this->post_inputs[$key] = $_POST[$key];
        } else {
            $this->post_inputs[$key] = $default;
        }
    }

    public function dispatch(): array {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return $this->get_inputs;
        } 
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            return $this->post_inputs;
        }

        return [];
    }
    
}


?>