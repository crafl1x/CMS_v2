<?php

namespace App\Auth;

use App\Controller\Controller;
use App\Form\Form;

class AuthController extends Controller{

    private function backToPage() {
        $form = new Form();
        $form->post('r', '/');
        $dispatch = $form->dispatch();

        var_dump($dispatch);
        header("location: " . $dispatch['r']);
        exit;

    }
    
    public function login() {

        $info = [];

        $error = false;
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
        
            $form = new Form();
            $form->post('email');
            $form->post('password');
            $info = $form->dispatch();

            $id = Auth::loginUser($info['email'], $info['password']);

            if ($id) {
                self::backToPage();
                
            }

            $error = true;

        }


        $this->render("/auth/auth.html.twig", [
            "option" => "login", 
            "info" => $info, 
            "error" => $error
        ]);
    }

    public function signup() {

        $requirements = [
            "passwordLength" => ["message" => "Minimálně 8 znaků", "status" => ""],
            "passwordLowercase" => ["message" => "Alespoň 1 malé písmeno", "status" => ""],
            "passwordUppercase" => ["message" => "Alespoň 1 velké písmeno", "status" => ""],
            "passwordNumber" => ["message" => "Alespoň 1 číslo", "status" => ""]
        ];

        $error = false;

        $inValid = [
            "email" => false,
            "password" => false,
            "passwordDismatch" => false
        ];

        $info = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
        
            $form = new Form();
            $form->post('email');
            $form->post('password');
            $form->post('passwordVerify');
            $info = $form->dispatch();


            $passwordCorrect = 0;
            if (filter_var($info['email'],FILTER_VALIDATE_EMAIL)) {

                if (strlen($info['password']) < 8) {
                    $requirements['passwordLength']['status'] = "wrong";
                } else {
                    $requirements['passwordLength']['status'] = "correct";
                    $passwordCorrect++;
                }

                if (!preg_match('@[a-z]@', $info['password'])) {
                    $requirements['passwordLowercase']['status'] = "wrong";
                } else {
                    $requirements['passwordLowercase']['status'] = "correct";
                    $passwordCorrect++;
                }

                if (!preg_match('@[A-Z]@', $info['password'])) {
                    $requirements['passwordUppercase']['status'] = "wrong";
                } else {
                    $requirements['passwordUppercase']['status'] = "correct";
                    $passwordCorrect++;
                }

                if (!preg_match('@[0-9]@', $info['password'])) {
                    $requirements['passwordNumber']['status'] = "wrong";
                } else {
                    $requirements['passwordNumber']['status'] = "correct";
                    $passwordCorrect++;
                }

            } else {
                $inValid['email'] = true;
            }

            if ($passwordCorrect == 4) {
                if ($info['password'] === $info['passwordVerify']) {
                    
                    $error = !Auth::registerUser($info['email'], $info['password']);
                    if (!$error) {
                        $id = Auth::loginUser($info['email'], $info['password']);

                        if ($id) {
                            self::backToPage();
                        }
                    }
                    
                } else {
                    $inValid['passwordDismatch'] = true;
                }
            } else {
                $inValid['password'] = true;
            }

        }

        

        $this->render("/auth/auth.html.twig", [
            "option" => "signup", 
            "requirements" => $requirements, 
            "error" => $error, 
            "info" => $info, 
            "inValid" => $inValid 
        ]);
    }
}

?>