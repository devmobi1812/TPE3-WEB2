<?php
require_once "app/models/LibrosModel.php";
require_once "app/views/AutoresView.php";
require_once "helpers/PaginationHelper.php";

class LibrosController {
    private $model;
    private $view;
    private $autoresModel;

    function __construct(){
        $this->model = new LibrosModel();
        $this->view = new AutoresView();
    }

    public function getAll($req) {
        if(isset($_GET['paginate']) && $_GET['paginate'] == true){
            $libros = $this->model->allPaginated(PaginationHelper::getPage($req));
        }else{
            $libros = $this->model->all(PaginationHelper::getSort($req), PaginationHelper::getFilter($req));            
        }
        $this->view->response($libros, 200);
    }
    public function get($req){
        if(empty($req->params->ID)){
            return $this->getAll($req);
        }else{
            $tarea = $this->model->find($req->params->ID);
            if($tarea){
                return $this->view->response($tarea, 200);
            }else{
                return $this->view->response("No existe el libro a obtener.", 404);
            }
        }
    }
    public function add($req){
        if(empty($req)){
            return $this->getAll($req);
        }else{
            $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas"];
            $libro = new stdClass();
            $isValidLibro = true;
            foreach($campos as $campo){
                $libro->$campo = $req->body->$campo ?? false;
                $isValidLibro = $isValidLibro && $libro->$campo;
            }
            if($isValidLibro){
                $this->model->create($libro);
                return $this->view->response("Libro creado exitosamente".$descripcion,200);
            }else{
                $this->view->response("No se pude crear el libro porque hay campos vacios.", 404);
            }
        }
    }
    public function update($req){
        if(empty($req)){
            return $this->getAll($req);
        }else{
            $id=$req->params->ID;
            $libro = $this->model->find($id);
            if($libro){
                $campos = ["isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas", "old_isbn"];
                $libro = new stdClass();
                $isValidLibro = true;
                foreach($campos as $campo){
                    $libro->$campo = $req->body->$campo ?? false;
                    $isValidLibro = $isValidLibro && $libro->$campo;
                }

                if($isValidLibro){
                    $this->model->update($libro);
                    $this->view->response("El libro con id ".$id." se actualizo de forma correcta.", 200);
                }else{
                    $this->view->response("El libro no se puede actualizar porque hay campos vacíos.", 404);
                }
            }else{
                $this->view->response("El libro no existe.", 404);
            }
        }
    }
    public function delete($req){
        if(empty($req->params->ID)){
            return $this->getAll($req);
        }else{
            $libro = $this->model->find($req->params->ID);
            if($libro){
                $this->model->delete($req->params->ID);
                $this->view->response("El libro ".$libro->titulo." se elimino correctamente.", 200);
            }else{
                $this->view->response("El libro con la id ".$req->params->ID." no se encuentra en registrado.", 404);
            }
        }
    }
}
?>