<?php
require_once "helpers/JWTHelper.php";
require_once "helpers/PaginationHelper.php";
require_once "app/views/APIView.php";
require_once "app/models/UsuariosModel.php";
class TokenController {
    private $view;
    private $model;
    public function __construct(){
        $this->model = new UsuariosModel();
        $this->view = new APIView();
    }
    public function JWT($req){
        if(empty($req->body->username)||empty($req->body->password)){
            return $this->view->response("No fue posible validar el inicio de sesión. Usuario o contraseña no cargada.", 401);
        }

        $user = new stdClass();
        $user->nombre = $req->body->username;
        $user->password = $req->body->password;
        $token = JWTHelper::login($user);

        if(!$token){
            return $this->view->response("No fue posible validar el inicio de sesión. Credenciales incorrectas.", 401);
        }

        return $this->view->response("Token generado exitosamente", 200, [
            "Authorization" => "Bearer {$token}"
        ]);
    }
}
?>