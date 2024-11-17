<?php 
require_once "app/models/UsuariosModel.php";
require_once "config/config.php";
    class JWTHelper{
        private static $userModel;

        public static function loadModel(){
            if(self::$userModel == null){
                self::$userModel = new UsuariosModel();
            }
        }
        public static function login($user){
            self::loadModel();
            $fetchedUser = self::$userModel->findByUsername($user->nombre);
            if(!empty($fetchedUser) && password_verify($user->password, $fetchedUser->password)){
                    return self::generateToken($fetchedUser);
            }
        }
        
        public static function decodeJWT($token) {
            $elements = explode(".", $token);
            if (count($elements) < 3) {
                return false;
            }
    
            $header = json_decode(base64_decode($elements[0]));
            $payload = json_decode(base64_decode($elements[1]));
            $signature = base64_decode($elements[2]);
    
            return [$header, $payload, $signature];
        }
    
        public static function generateToken($user) {
            $header = json_encode([
                'typ' => 'JWT',
                'alg' => 'HS256'
            ]);
            $payload = json_encode([
                'iat' => time(),
                'nbf' => time(),
                'exp' => time() + TOKEN_EXPIRATION_TIME * 3600,
                'user_id' => $user->id,
                'role' => $user->rol_nombre
            ]);
            $tokenArray = self::encodeJWTToken($header, $payload);
            return implode(".", $tokenArray);
        }

        public static function encodeJWTToken($header, $payload){
            $base64Header = base64_encode($header);
            $base64Payload = base64_encode($payload);
    
            $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, SECRET, true);
            $base64Signature = base64_encode($signature);

            return [$base64Header, $base64Payload, $base64Signature];
        }
    
        public static function verifyJWTToken($req) {
            if (!isset($req->headers->Authorization)) {
                return false;
            }
            
            $tokenArr = explode(" ", $req->headers->Authorization);
            if(count($tokenArr) != 2){
                return false;
            }

            $sections = self::decodeJWT($tokenArr[1]);
            if (!$sections) {
                return false;
            }

            if ($sections[1]->exp <= time()) {
                return false;
            }
            
            $base64Header = base64_encode(json_encode($sections[0]));
            $base64Payload = base64_encode(json_encode($sections[1]));
            
            $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, SECRET, true);
            return $sections[2] === $signature;

        }

        
    }
?>