<?php
require_once "app/models/LibrosModel.php";
require_once "app/models/AutoresModel.php";
require_once "app/views/LibrosView.php";
require_once "helpers/AuthHelper.php";

class LibrosController {
    private $model;
    private $view;
    private $autoresModel;

    function __construct(){
        $this->model = new LibrosModel();
        $this->autoresModel = new AutoresModel();
        $this->view = new LibrosView();
    }

    public function index(){
        $this->view->index($this->model->all());
    }

    public function show($id){
        $libro = $this->model->find($id);
        if($libro){
            $this->view->show($libro);
        }else{
            echo "Libro con ISBN ".$id." no encontrado.";
        }
    }

    public function create(){
        if(AuthHelper::isAdmin()){
            $this->view->create($this->autoresModel->all());
        }else{
            header("Location:".BASE_URL."iniciar-sesion");
            die();
        }
    }

    public function store(){
        if(AuthHelper::isAdmin()){
            $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas"];
            $libro = new stdClass();
            $isValidLibro = true;
            foreach($campos as $campo){
                $libro->$campo = $_POST[$campo] ?? false;
                $isValidLibro = $isValidLibro && $libro->$campo;
            }
            if($isValidLibro){
                $this->model->create($libro);
                header("Location:".BASE_URL."libros/$libro->isbn");
                die();
            }else{
                header("Location:".BASE_URL."libros/crear");
                die();    
            }

        }else{
            header("Location:".BASE_URL."iniciar-sesion");
            die();
        }
    }

    public function edit($id){
        $libro = $this->model->find($id);
        if(AuthHelper::isAdmin()){
            if($libro){
                $this->view->edit($libro, $this->autoresModel->all(), $id);
            }else{
                echo "Libro con ISBN ".$id." no encontrado.";
            }
        }else{
            header("Location:".BASE_URL."iniciar-sesion");
            die();
        }
    }
    public function update(){
        if(AuthHelper::isAdmin()){
            $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas", "old_isbn"];
            $libro = new stdClass();
            $isValidLibro = true;
            foreach($campos as $campo){
                $libro->$campo = $_POST[$campo] ?? false;
                $isValidLibro = $isValidLibro && $libro->$campo;
            }
            if($isValidLibro){
                $this->model->update($libro);
                header("Location:".BASE_URL."libros/$libro->isbn");
                die();
            }else{
                header("Location:".BASE_URL."libros/editar/$libro->old_isbn");
                die();    
            }

        }else{
            header("Location:".BASE_URL."iniciar-sesion");
            die();
        }
    }

    public function destroy($id){
        if(AuthHelper::isAdmin()){
            $this->model->delete($id);
            header("Location:".BASE_URL."libros");
            die();
        }else{
            header("Location:".BASE_URL."iniciar-sesion");
            die();
        }
    }
}
?>