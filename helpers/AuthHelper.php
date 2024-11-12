<?php 
require_once "app/models/UsuariosModel.php";
require_once "config/config.php";
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
                    return self::generateToken($fetchedUser);
                }
            }
        }

        //TODO: todo esto es tentativo. Referencia: https://dzone.com/articles/create-your-jwts-from-scratch
        public static function base64UrlEncode($data) {
            $base64 = base64_encode($data);
            $base64Url = str_replace(['+', '/', '='], ['-', '_', ''], $base64);
            return $base64Url;
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
    
            $base64UrlHeader = self::base64UrlEncode($header);
            $base64UrlPayload = self::base64UrlEncode($payload);
    
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET, true);
            $base64UrlSignature = self::base64UrlEncode($signature);
    
            return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        }
    
        public static function verifyJWTToken($req) {
            if (isset($req->headers->Authorization)) {
                $token = explode(" ", $req->headers->Authorization)[1];
                $sections = self::decodeJWT($token);
    
                if (!$sections) {
                    return false;
                }
    
                if ($sections[1]->exp <= time()) {
                    return false;
                }
    
                $base64UrlHeader = self::base64UrlEncode(json_encode($sections[0]));
                $base64UrlPayload = self::base64UrlEncode(json_encode($sections[1]));
                $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET, true);
    
                return $sections[3] === $signature;
            } else {
                return false;
            }
        }

        
    }
?>