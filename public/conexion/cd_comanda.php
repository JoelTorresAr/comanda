<?php
if (isset($_SERVER['HTTP_ORIGIN'])) { 
  // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one 
  // you want to allow, and if so: 
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}"); 
  header('Access-Control-Allow-Credentials: true'); 
  header('Access-Control-Max-Age: 86400'); // cache for 1 day 
 } 
$comanda= filter_input(INPUT_GET, 'comanda');
if ($comanda==1) {
  $ip = filter_input(INPUT_GET, 'ip');
  $pin = filter_input(INPUT_GET, 'pin');
  $piso = filter_input(INPUT_GET, 'piso');
  $id_cmd = filter_input(INPUT_GET, 'parm_id_cmd');
  $id_mesero = filter_input(INPUT_GET, 'parm_id_mesero');
  
  $url = $ip."?nomFun=tb_revisar_cmd&parm_pin=".$pin."&parm_piso=".$piso."&parm_id_cmd=".$id_cmd."&parm_tipocmd=1&parm_id_mesero=".$id_mesero."&parm_tipo=M$";
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
}


   

///?nomFun=tb_new_cmd&parm_pin=${this.pin}&parm_piso=${this.pisoActual}&parm_id_mesas=${this.mesaId}&parm_num=${this.numcomen}&parm_tipocmd=1&parm_id_mesero=${id}&parm_tipo=M$
if ($comanda==2) {
  $ip = filter_input(INPUT_GET, 'ip');
  $pin = filter_input(INPUT_GET, 'pin');
  $piso = filter_input(INPUT_GET, 'piso');
  $id_mesa = filter_input(INPUT_GET, 'parm_id_mesas');
  $par_num = filter_input(INPUT_GET, 'parm_num');
  $id_mesero = filter_input(INPUT_GET, 'parm_id_mesero');
  
  $url = $ip."?nomFun=tb_new_cmd&parm_pin=".$pin."&parm_piso=".$piso."&parm_id_mesas=".$id_mesa."&parm_num=".$par_num."&parm_tipocmd=1&parm_id_mesero=".$id_mesero."&parm_tipo=M$";
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
}