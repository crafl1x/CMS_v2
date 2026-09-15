<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\AdminPanel\AdminPanel;
use App\Auth\Auth;
use App\Router\Router;

AdminPanel::enable();
Auth::enable();

Router::add("/admin", "\App\AdminPanel\DashboardController", "endpoint");
Router::add("/admin/users", "\App\AdminPanel\UsersController", "endpoint");

Router::add("/", "\App\Controller\HomeController", "endpoint");
Router::add("/post", "\App\Controller\PostController", "endpoint");

Router::dispatch();

?>