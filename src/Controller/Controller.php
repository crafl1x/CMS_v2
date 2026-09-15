<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use App\Auth\Auth;

class Controller {

    protected Environment $twig;

    private array $twig_globals = [];

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . "/../../templates");
        $this->twig = new Environment($loader);

        $this->twig_globals["isLogin"] = Auth::isLogin();
    }

    public function render(string $template, array $data = []) {
        echo $this->twig->render($template, array_merge($this->twig_globals, $data));
    }

}

?>