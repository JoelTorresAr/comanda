<?php
if (isset($_SERVER['HTTP_ORIGIN'])) { 
  // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one 
  // you want to allow, and if so: 
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}"); 
  header('Access-Control-Allow-Credentials: true'); 
  header('Access-Control-Max-Age: 86400'); // cache for 1 day 
 } 
$ip = filter_input(INPUT_GET, 'ip');
$pin = filter_input(INPUT_GET, 'pin');
$url = $ip."?nomFun=tb_login&parm_cod=Xyfk8Gixnf&parm_new=0&parm_pin=".$pin."&parm_tipo=M$";
    $ch_factura = curl_init();
    curl_setopt($ch_factura, CURLOPT_URL, $url);
    curl_setopt($ch_factura, CURLOPT_POST, 1);
    curl_setopt($ch_factura, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_factura, CURLOPT_POSTFIELDS, $_GET);
    curl_setopt($ch_factura, CURLOPT_RETURNTRANSFER, true);
    $respuesta_factura = curl_exec($ch_factura);
    curl_close($ch_factura);
 
  //echo $array = json_decode( json_encode( $respuesta_factura ), true );
  echo $respuesta_factura;
   

