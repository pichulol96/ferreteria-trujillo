<?php
include("api/ip/IP.php");
$objetoIP = new IP();
$ip = $objetoIP->obtenerIP();
$url= "http://${ip}/ferreteria-trujillo/api";
return $url;
?>