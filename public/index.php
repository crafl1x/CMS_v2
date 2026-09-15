<?php
require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

use App\Auth\Auth;
use App\Router\Router;

Auth::enable();

Router::add("/admin", "\App\AdminPanel\DashboardController", "endpoint");
Router::add("/admin/users", "\App\AdminPanel\UsersController", "endpoint");
Router::add("/admin/users/edit", "\App\AdminPanel\UsersController", "edit");

Router::add("/", "\App\Controller\HomeController", "endpoint");
Router::add("/post", "\App\Controller\PostController", "endpoint");

Router::dispatch();

?>