<?php
require_once "app/models/LibrosModel.php";
require_once "app/views/APIView.php";
require_once "helpers/PaginationHelper.php";
require_once "helpers/JWTHelper.php";

class LibrosController {
    private $model;
    private $view;

    function __construct(){
        $this->model = new LibrosModel();
        $this->view = new APIView();
    }

    public function getAll($req) {
        if(isset($req->query->paginate) && $req->query->paginate == true){
            $libros = $this->model->allPaginated(PaginationHelper::getPage($req));
        }else{
            $libros = $this->model->all(PaginationHelper::getSort($req), PaginationHelper::getFilter($req));            
        }
        return $this->view->response($libros, 200);
    }

    public function get($req){
        if(empty($req->params->ID)){
            return $this->getAll($req);
        }

        $tarea = $this->model->find($req->params->ID);
        if(!$tarea){
            return $this->view->response("No existe el libro a obtener.", 404);    
        }
        
        return $this->view->response($tarea, 200);
    }

    public function add($req){
        if(empty($req)){
            return $this->getAll($req);
        }
        if(!JWTHelper::verifyJWTToken($req)){
            return $this->view->response("No tienes permiso para acceder a este recurso", 401);
        }

        $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas"];
        $libro = new stdClass();
        $isValidLibro = true;
        foreach($campos as $campo){
            $libro->$campo = $req->body->$campo ?? false;
            $isValidLibro = $isValidLibro && $libro->$campo;
        }

        if(!$isValidLibro){
            return $this->view->response("No se pude crear el libro porque hay campos vacios.", 404);
        }

        $this->model->create($libro);
        return $this->view->response("Libro creado exitosamente",201);
    }

    public function update($req){
        if(empty($req->params->ID)){
            return $this->getAll($req);
        }
        if(!JWTHelper::verifyJWTToken($req)){
            return $this->view->response("No tienes permiso para acceder a este recurso", 401);
        }

        $libro = $this->model->find($req->params->ID);
        if(!$libro){
            return $this->view->response("El libro no existe.", 404);
        }

        $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas"];
        $libro = new stdClass();
        $isValidLibro = true;
        foreach($campos as $campo){
            $libro->$campo = $req->body->$campo ?? false;
            $isValidLibro = $isValidLibro && $libro->$campo;
        }
        $libro->old_isbn = $req->params->ID;
        if(!$isValidLibro){
            return $this->view->response("El libro no se puede actualizar porque hay campos vacíos.", 400);
        }

        $this->model->update($libro);
        return $this->view->response("El libro con id ".$req->params->ID." se actualizo de forma correcta.", 201);
    }
    public function delete($req){
        if(empty($req->params->ID)){
            return $this->getAll($req);
        }
        if(!JWTHelper::verifyJWTToken($req)){
            return $this->view->response("No tienes permiso para acceder a este recurso", 401);
        }

        $libro = $this->model->find($req->params->ID);
        if(!$libro){
            return $this->view->response("El libro con la id ".$req->params->ID." no se encuentra registrado.", 404);
        }

        $this->model->delete($req->params->ID);
        return $this->view->response("El libro ".$libro->titulo." se elimino correctamente.", 200);
    }
}
?>