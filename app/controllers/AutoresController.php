<?php
    require_once('app/models/AutoresModel.php');
    require_once "app/views/APIView.php";
    class AutoresController {
        private $model;
        private $view;
        function __construct(){
            $this->model= new AutoresModel();
            $this->view = new APIView();
        }

        public function getAll($req) {
            if(isset($req->query->paginate) && $req->query->paginate == true){
                $autores = $this->model->allPaginated(PaginationHelper::getPage($req));
            }else{
                $autores = $this->model->getAll(PaginationHelper::getSort($req), PaginationHelper::getFilter($req));            
            }
            if($autores){
                return $this->view->response($autores, 200);
            }else{
                return $this->view->response($autores, 404);
            }
            
        }

        public function get($id){
            $autor = $this->model->find($id->params->ID);
            if($autor){
                return $this->view->response($autor, 200);
            }else{
                return $this->view->response("No existe el autor a obtener.", 404);
            }
        }

        public function add($req){
            if(empty($req)){
                return $this->getAll($req);
            }
            if(!JWTHelper::verifyJWTToken($req)){
                return $this->view->response("No tienes permiso para acceder a este recurso", 401);
            }
    
            $campos = ["nombre", "biografia", "imagen"];
            $autor = new stdClass();
            $esAutorValido = true;
            foreach($campos as $campo){
                $autor->$campo = $req->body->$campo ?? false;
                $esAutorValido = $esAutorValido && $autor->$campo;
            }
    
            if(!$esAutorValido){
                return $this->view->response("No se pude crear el autor porque hay campos vacios.", 400);
            }
    
            $this->model->create($autor);
            return $this->view->response("Autor creado exitosamente",201);
        }

        public function update($req){
            if(empty($req->params->ID)){
                return $this->getAll($req);
            }
            if(!JWTHelper::verifyJWTToken($req)){
                return $this->view->response("No tienes permiso para acceder a este recurso", 401);
            }
            $autorExiste = $this->model->find($req->params->ID);
            if(!$autorExiste){
                return $this->view->response("El autor no existe.", 404);
            }

            $campos = ["nombre", "biografia", "imagen"];
            $autor = new stdClass();
            $esAutorValido = true;
            foreach($campos as $campo){
                $autor->$campo = $req->body->$campo ?? false;
                $esAutorValido = $esAutorValido && $autor->$campo;
            }

            $autor->id=$req->params->ID;
    
            if(!$esAutorValido){
                return $this->view->response("No se pude actualizr el autor porque hay campos vacios.", 400);
            }
    
            $this->model->update($autor);
            return $this->view->response("Autor actualizado exitosamente",201);
        }

        public function delete($req){
            if(empty($req->params->ID)){
                return $this->getAll($req);
            }
            if(!JWTHelper::verifyJWTToken($req)){
                return $this->view->response("No tienes permiso para acceder a este recurso", 401);
            }
    
            $autor = $this->model->find($req->params->ID);
            if(!$autor){
                return $this->view->response("El autor con el id ".$req->params->ID." no se encuentra registrado.", 404);
            }
    
            $this->model->delete($req->params->ID);
            return $this->view->response("El auto se elimino correctamente.", 200);
        }

    }
?>