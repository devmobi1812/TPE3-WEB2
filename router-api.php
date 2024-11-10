<?php
    require_once 'libs/router.php';
    require_once 'api/controllers/TareasController.php';

    $router = new Router();

    $router->addRoute('libros', 'GET', 'TareasController', 'getAll');
    $router->addRoute('libro/:ID', 'GET', 'TareasController', 'get');
    $router->addRoute('libro', 'POST', 'TareasController', 'add');
    $router->addRoute('libro/:ID', 'DELETE', 'TareasController', 'borrar');
    $router->addRoute('libro/:ID', 'PUT', 'TareasController', 'update');

    $router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);