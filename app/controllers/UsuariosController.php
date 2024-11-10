<?php
require_once "helpers/AuthHelper.php";
require_once "app/views/UsuariosView.php";
require_once "app/models/UsuariosModel.php";
class UsuariosController {
    private $view;
    private $model;
    public function __construct(){
        $this->view = new UsuariosView();
        $this->model = new UsuariosModel();
    }
    
    public function loginForm(){
        $this->view->login();
    }
    public function login(){
        $username = $_POST["username"];
        $password = $_POST["password"];
        if(!empty($username)&&!empty($password)){
            $user = new stdClass();
            $user->nombre = $username;
            $user->password = $password;
            AuthHelper::login($user);
            if(AuthHelper::loggedUser()){
                header("Location:".BASE_URL."inicio");
            }else{
                header("Location:".BASE_URL."iniciar-sesion");
            }
        } 
    }

    public function logout(){
        AuthHelper::logout();
        header("Location:".BASE_URL."inicio");
    }


    //  ESTE REGISTRAR ES TEMPORAL, PQ EN REALIDAD HAY QUE HACERLO MEDIANTE UNA PAGINA DE REGISTRACION, Y OBTENER LOS DATOS DE UN FORMULARIO PARA PODER CREAR EL USUARIO.
    //  PERO COMO NO ERA UNA TAREA A REALIZAR, SIMPLEMENTE HICIMOS ESTE METODO PARA MOSTRAR EL TEMA DEL HASHEO Y DE COMO SE CREO EL USUARIO WEBADMIN
    
    function registrarTMP(){  
        $nombre="webadmin";
        $password="admin";
        $verificarPassword="admin";
        $rol=1;

        if($password==$verificarPassword){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            $usuario=new stdClass();
            $usuario->nombre=$nombre;
            $usuario->password=$hash;
            $usuario->rol=$rol;

            $this->model->create($usuario);
        }else{
            return print("Error, las contraseñas no son iguales");
        }
    }
}
?>