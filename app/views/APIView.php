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
              401 => "Unauthorized", 
              404 => "Not found",
              500 => "Internal Server Error"
            );
            return (isset($status[$code]))? $status[$code] : $status[500];
          }
    }