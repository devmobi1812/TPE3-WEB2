<?php
    require_once 'libs/router.php';
    require_once 'app/controllers/LibrosController.php';
    require_once 'app/controllers/AutoresController.php';
    require_once 'app/controllers/TokenController.php';


    $router = new Router();
/*
    $router->addRoute('libros', 'GET', 'TareasController', 'getAll');
    $router->addRoute('libro/:ID', 'GET', 'TareasController', 'get');
    $router->addRoute('libro', 'POST', 'TareasController', 'add');
    $router->addRoute('libro/:ID', 'DELETE', 'TareasController', 'borrar');
    $router->addRoute('libro/:ID', 'PUT', 'TareasController', 'update');
*/
    $router->addRoute('libros', 'GET', 'LibrosController', 'getAll');
    $router->addRoute('libros/:ID', 'GET', 'LibrosController', 'get');
    $router->addRoute('libros', 'POST', 'LibrosController', 'add');
    $router->addRoute('libros/:ID', 'DELETE', 'LibrosController', 'delete');
    $router->addRoute('libros/:ID', 'PUT', 'LibrosController', 'update');
    
    $router->addRoute('autores', 'GET', 'AutoresController', 'getAll');
    $router->addRoute('autores/:ID', 'GET', 'AutoresController', 'get');
    $router->addRoute('autores', 'POST', 'AutoresController', 'add');
    $router->addRoute('autores/:ID', 'DELETE', 'AutoresController', 'delete');
    $router->addRoute('autores/:ID', 'PUT', 'AutoresController', 'update');
    
    $router->addRoute('login', 'POST', 'TokenController', 'JWT');

    $router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);