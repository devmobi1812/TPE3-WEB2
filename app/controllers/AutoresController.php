<?php
    require_once('app/models/AutoresModel.php');
    require_once('app/views/AutoresView.php');
    class AutoresController {
        private $model;
        private $view;
        protected $data;
        function __construct(){
            $this->data = file_get_contents("php://input");
            $this->model= new AutoresModel();
        }

        public function getAll() {
            $autores = $this->model->all();
            if($autores){
                $this->view->response($autores, 200);
            }else{
                $this->view->response($autores, 404);
            }
            
        }
        function index(){
            $autores=$this->model->all();
            $this->view->verAutores($autores);
        }

        function show($id){
            $libros=$this->model->getLibrosAutor($id);
            $this->view->verAutor($libros);
        }

        function create(){
            if(AuthHelper::isAdmin()){
                $this->view->crearAutor();
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
                die();
            }
            
        }

        function store(){
            if(AuthHelper::isAdmin()){
                if(!empty($_POST['nombre']) && !empty($_POST['biografia'])){
                    $nombre = $_POST['nombre'];
                    $biografia = $_POST['biografia'];
                    $imagen="";

                    if(empty($_POST['imagen'])){
                        $imagen="https://img.freepik.com/foto-gratis/chico-guapo-seguro-posando-contra-pared-blanca_176420-32936.jpg?w=1380&t=st=1728084474~exp=1728085074~hmac=5e4b0abebdca24f5367b3d99334c582cb0a4fb53b61dcbf454589a609cc395a2";
                    }else{
                        $imagen = $_POST['imagen'];
                    }
                    
                    $autor = new stdClass();
                    $autor->nombre=$nombre;
                    $autor->biografia=$biografia;
                    $autor->imagen=$imagen;
            
                    $this->model->create($autor);
                    header('Location: '.BASE_URL.'autores');
                    die(); 
                }else{
                    header("Location:".BASE_URL."autores/crear");
                    die();
                }
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
                die();
            }
            
        }

        function destroy($id){
            if(AuthHelper::isAdmin()){
                $this->model->delete($id);
                header('Location: '.BASE_URL.'autores');
                die();
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
                die();
            }
            
        }

        function edit($id){
            if(AuthHelper::isAdmin()){
                $autor=$this->model->find($id);
                $this->view->editarAutor($autor);
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
                die();
            }
            
        }

        function update(){
            if(AuthHelper::isAdmin()){
                $id=$_POST['id'];
                if(!empty($_POST['nombre']) && !empty($_POST['biografia'])){
                    $nombre = $_POST['nombre'];
                    $biografia = $_POST['biografia'];
                    $imagen="";

                    if(empty($_POST['imagen'])){
                        $imagen="https://img.freepik.com/foto-gratis/chico-guapo-seguro-posando-contra-pared-blanca_176420-32936.jpg?w=1380&t=st=1728084474~exp=1728085074~hmac=5e4b0abebdca24f5367b3d99334c582cb0a4fb53b61dcbf454589a609cc395a2";
                    }else{
                        $imagen = $_POST['imagen'];
                    }
                    
                    $autor = new stdClass();
                    $autor->id=$id;
                    $autor->nombre=$nombre;
                    $autor->biografia=$biografia;
                    $autor->imagen=$imagen;
            
                    $this->model->update($autor);
                    
                    header('Location: '.BASE_URL.'autores');
                    die(); 
                }else{
                    header('Location: '.BASE_URL.'autores/editar/'.$id);
                    die(); 
                }
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
                die();
            }
            
        }
    }
?>