<?php
require_once "helpers/AuthHelper.php";
require_once "app/views/AutoresView.php";
require_once "app/models/UsuariosModel.php";
class UsuariosController {
    private $view;
    private $model;
    public function __construct(){
        $this->model = new UsuariosModel();
        $this->view = new AutoresView();
    }
    public function login($req){
        $username = $req->body->username;
        $password = $req->body->password;
        if(!empty($username)&&!empty($password)){
            $user = new stdClass();
            $user->nombre = $username;
            $user->password = $password;
            $token = AuthHelper::login($user);
            if($token){
                $this->view->response("Token generado exitosamente", 200, [
                    "Authorization" => "Bearer {$token}"
                ]);
            }else{
                $this->view->response("No fue posible validar el inicio de sesión. Credenciales incorrectas.", 401);
            }
        } 
    }
}
?>