<?php

namespace App\Auth;

use App\Database\Database;
use App\Router\Router;
use PDO;
use PDOException;

class Auth {

    private static bool $enabled = false;
    private static array $avaibleRoles = ['admin', 'editor', 'user'];

    public static function enable() {
        if (!self::$enabled) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            Router::add("/login", "\App\Auth\AuthController", "login");
            Router::add("/signup", "\App\Auth\AuthController", "signup");


            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = null;
            }

            self::$enabled = true;
        }
    }

    public static function getAvaibleRoles(): array {
        return self::$avaibleRoles;
    }

    public static function getUserID() : ?int {
        return $_SESSION['user_id'] ?? null;
    }

    private static function setUserID(?int $id): void {
        $_SESSION['user_id'] = $id;
    }

    public static function getUserRole(): string {
        if (self::isLogin()) {
            $db = Database::connect();
            $conn = $db->prepare("SELECT `role` FROM `users` WHERE `id` = :id");
            $conn->bindValue(":id", self::getUserID(), PDO::PARAM_INT);
            $conn->execute();

            $req = $conn->fetch();

            if ($req) {
                return $req['role'];
            }
        }

        return 'user';
    }

    public static function setUserRole(string $role): void {
        if (in_array($role, self::$avaibleRoles)) {
            try {
                $db = Database::connect();
                $conn = $db->prepare("UPDATE `users` SET `role`=:role WHERE `id`=:id");
                $conn->bindValue(":id", self::getUserID(), PDO::PARAM_INT);
                $conn->bindValue(":role", $role, PDO::PARAM_STR);
                $conn->execute();
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
        }
    }

    public static function isUserMinimalRole(string $minimalRole): bool {
        if (array_search(self::getUserRole(), self::$avaibleRoles) <= array_search($minimalRole, self::$avaibleRoles)){
            return true;
        }
        return false;
    }

    public static function isLogin(): bool {
        if (self::getUserID() and self::getEmailbyID(self::getUserID())) {
            return true;
        }

        return false;
    }

    

    public static function requireLogin(string $minimalRole = 'user') {
        if (!self::isLogin()) {
            self::openLogin();
        }

        if (!self::isUserMinimalRole($minimalRole)){
            echo "403 No Permision";
        }

    }

    public static function openLogin() {
        $uri = $_SERVER['REQUEST_URI'];
    
        header("location: /login?r=$uri");
        exit;
    }

    public static function getEmailbyID(?int $id) : ?string {

        $db = Database::connect();
        $conn = $db->prepare("SELECT `email` FROM `users` WHERE `id` = :id");
        $conn->bindValue(":id", $id, PDO::PARAM_INT);
        $conn->execute();

        $req = $conn->fetch();

        if ($req) {
            return $req['email'];
        }

        return null;
    }

    public static function getIDbyEmail(string $email): ?int {
        
        $db = Database::connect();
        $conn = $db->prepare("SELECT `id` FROM `users` WHERE `email` = :email");
        $conn->bindValue(":email", $email, PDO::PARAM_STR);
        $conn->execute();
        
        $req = $conn->fetch();

        if ($req) {
            return $req['id'];
        }

        return false;
    }

    public static function registerUser(string $email, string $password): bool {

        if (self::getIDbyEmail($email)) {
            return false;
        }
        
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $db = Database::connect();
            $conn = $db->prepare("INSERT INTO `users`(`email`, `hash`) VALUES (:email, :hash)");
            $conn->bindValue(":email", $email, PDO::PARAM_STR);
            $conn->bindValue(":hash", $hash, PDO::PARAM_STR);
            $conn->execute();

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());   
        }

        if (self::getIDbyEmail($email)) {
            return true;
        }
    
        return false;
    }

    public static function loginUser(string $email, string $password): ?int {

        $db = Database::connect();
        $conn = $db->prepare("SELECT `id`,`hash` FROM `users` WHERE `email` = :email");
        $conn->bindValue(":email", $email, PDO::PARAM_STR);
        $conn->execute();
        
        $req = $conn->fetch();

        if ($req) {
            if (password_verify($password, $req['hash'])) {
                self::setUserID($req['id']);
                return $req['id'];
            }
        }

        return null;
    }

}

?>