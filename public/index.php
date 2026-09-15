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
Router::add("/admin/users/delete", "\App\AdminPanel\UsersController", "delete");

Router::add("/admin/posts", "\App\AdminPanel\PostsController", "endpoint");
Router::add("/admin/posts/new", "\App\AdminPanel\PostsController", "new");
Router::add("/admin/posts/edit", "\App\AdminPanel\PostsController", "edit");
Router::add("/admin/posts/delete", "\App\AdminPanel\PostsController", "delete");

Router::add("/", "\App\Controller\HomeController", "endpoint");
Router::add("/post", "\App\Controller\PostController", "endpoint");

Router::dispatch();

?>