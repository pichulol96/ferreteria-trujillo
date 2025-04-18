<?php
    class IP{
        public function obtenerIP(){
            include "api/db/conexion.php";
            $result = mysqli_query(
                $conexion,"SELECT descripcion_config FROM config where tipo_config = 'ip';");
            $ip;
            while($consulta = mysqli_fetch_array($result)){ 
                $ip= $consulta["descripcion_config"];
            }
            return $ip;
        }

        public function nuevaIP(){
            if (!empty($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];            
            if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            return $_SERVER["REMOTE_ADDR"];
            
        }
    }    
?>