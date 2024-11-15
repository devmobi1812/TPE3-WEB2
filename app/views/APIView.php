<?php

    class APIView {

        public function response($data, $status, $headers = []) {
            header("Content-Type: application/json");
            header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
            foreach ($headers as $key => $value) {
              header("$key: $value");
            }
            echo json_encode($data);
            die();
        }        

        private function _requestStatus($code){
            $status = array(
              200 => "OK",
              401 => "Sin Autorizacion", 
              404 => "Pagina no encontrada",
              500 => "Error de servidor"
            );
            return (isset($status[$code]))? $status[$code] : $status[500];
          }
    }