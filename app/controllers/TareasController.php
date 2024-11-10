<?php

    require_once('api/views/TareasView.php');
    require_once('app/models/task.model.php');

    class TareasController {

        protected $view;
        protected $model;
        protected $data;

        public function __construct() {
            $this->data = file_get_contents("php://input");

            $this->view = new TareasView();
            $this->model = new TaskModel();
        }

        public function getAll() {
            $tareas = $this->model->getTareas();
            if($tareas){
                $this->view->response($tareas, 200);
            }else{
                $this->view->response($tareas, 404);
            }
            
        }

        public function get($req){
            if(empty($req->params->ID)){
                return $this->getAll();
            }else{
                $tarea = $this->model->getTask($req->params->ID);
                if($tarea){
                    return $this->view->response($tarea, 200);
                }else{
                    return $this->view->response("No existe la tarea a obtener.", 404);
                }
            }
            
        }

        public function add($req){
            if(empty($req)){
                return $this->getAll();
            }else{
                $descripcion = $req->body->descripcion;
                $terminada = $req->body->terminada;
                $prioridad = $req->body->prioridad;

                if(!empty($descripcion)|| !empty($terminada)|| !empty($prioridad)){
                    $this->model->createTask($descripcion, $terminada, $prioridad);
                    return $this->view->response("Se puedo crear la tarea ".$descripcion,200);
                }else{
                    $this->view->response("No se pude crear la tarea porque hay campos vacios.", 404);
                }

            }
            
        }

        public function update($req){
            if(empty($req)){
                return $this->getAll();
            }else{
                $id=$req->params->ID;
                $tarea= $this->model->getTask($id);
                if($tarea){
                    $descripcion = $req->body->descripcion;
                    $terminada = $req->body->terminada;
                    $prioridad = $req->body->prioridad;
                    
                    if(!empty($descripcion)|| !empty($terminada)|| !empty($prioridad)){
                        $this->model->updateTask($descripcion, $terminada, $prioridad, $id);
                        $this->view->response("La tarea ".$id." se actualizo de forma correcta.", 200);
                    }else{
                        $this->view->response("La tarea no se puede actualizar porque hay campos vacios.", 404);
                    }
                }else{
                    $this->view->response("La tarea no existe.", 404);
                }
            }
        }

        public function borrar($req){
            if(empty($req->params->ID)){
                return  $this->getAll();
            }else{
                $tarea=$this->model->getTask($req->params->ID);
                if($tarea){
                    $this->model->deleteTask($req->params->ID);
                    $this->view->response("La tarea ".$req->params->ID." se elimino correctamente.", 200);
                }else{
                    $this->view->response("La tarea ".$req->params->ID." no se encuentra en registrada.", 404);
                }
            }
        }
        /* COMENTADO PQ LO HIZO MARIANO Y TODAVIA NO LO EXPLICO EUGE
        private function getData() {
            return json_decode($this->data);
        }

        public function add() {

            $nueva = $this->getData();

            $this->model->createTask($nueva->descripcion, 
                    $nueva->terminada, $nueva->prioridad);

            $this->view->response("Tarea creada", 200);
        }        

        public function get($params) {
            $id = $params[':ID'];

            $tarea = $this->model->getTask($id);

            if (isset($tarea) && ($tarea != null)) {
                $this->view->response($tarea, 200);
            } else {
                $this->view->response("Recurso no encontrado $id", 400);
            }

            
        }     
        
        public function borrar($params) {
            $id = $params[':ID'];

            $this->model->deleteTask($id);

            $this->view->response("Tarea eliminada", 200);
            
        }    

        public function update($params) {
            $id = $params[':ID'];
            $nueva = $this->getData();

            $anterior = $this->model->getTask($id);

            if (!isset($nueva->descripcion)) {
                $nueva->descripcion = $anterior->descripcion;
            }

            if (!isset($nueva->terminada)) {
                $nueva->terminada = $anterior->terminada;
            }

            if (!isset($nueva->prioridad)) {
                $nueva->prioridad = $anterior->prioridad;
            }

            $this->model->updateTask($nueva->descripcion, 
            $nueva->terminada, $nueva->prioridad, $id);

            $this->view->response("Tarea actualizada", 200);
            
        }    
*/

    }