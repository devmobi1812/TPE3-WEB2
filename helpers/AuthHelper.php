<?php 
require_once "app/models/UsuariosModel.php";
    class AuthHelper{
        private static $userModel;

        public static function loadModel(){
            if(self::$userModel == null){
                self::$userModel = new UsuariosModel();
            }
        }
        public static function login($user){
            self::loadModel();
            $fetchedUser = self::$userModel->findByUsername($user->nombre);
            if(!empty($fetchedUser)){
                if(password_verify($user->password, $fetchedUser->password)){
                    self::iniciarSession();
                    $_SESSION["user"] = $fetchedUser;
                }
            }
        }

        private static function iniciarSession(){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }

        public static function logout(){
            self::iniciarSession();
            //  Si usuario esta definido, cerrar session.
            if(isset($_SESSION['user'])){
                session_destroy();
            }
            
        }
        public static function loggedUser(){
            self::iniciarSession();
            return $_SESSION["user"] ?? false;
        }

        public static function isAdmin(){
            $user = self::loggedUser();
            return ($user) ? $user->rol_nombre == 'ADMIN' : false;
        }

        
    }
?>