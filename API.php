<?php
#Autor: Fran Oliveira
#e-mail:foliveirag@gmail.com
#PRAC1 Fundamentos de Ciberseguridad
# OAuth

  $client_id="77vangioyjigoo";
  $client_secret="vPEKLnbwfefCHaQb";

  #Función para conseguir un token a partir del codigo inicial.
 function GetToken($code,$URL,$data){
   global $client_id;
   global $client_secret;
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl,CURLOPT_URL,$URL);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);

    $respuesta = curl_exec($curl);
    $respuesta = explode("\n\r\n", $respuesta);
	  $headers = $respuesta[0];
    #echo $headers;
	  $body = $respuesta[1];
    curl_close($curl);
    
    #Eliminamos la URL del json incluido en el body

    $body= (str_replace("https://www.linkedin.com/oauth/v2/accessToken","",$body));
   
    #Convierte el JSON en un ARRAY
    $body=json_decode($body, True);
    
    return $body;

  }
  #Funcion para obtener la información del perfil del usuario
function getUserProfile($URL){

  $token="";
  $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $URL);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    $respuesta = curl_exec($curl);
    $respuesta = explode("\n\r\n", $respuesta);
	  $headers = $respuesta[0];
	  $body = $respuesta[1];
    curl_close($curl);
   
    if (str_contains($headers,'200 OK')){
      return $body;

    }
    else{
       echo $headers;
       return false;
    }
    

}
# Función para publicar contenido, recibe datos a publicar y la url de la api rest en Linkedin
function Publish($URL,$data){
 
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
   curl_setopt($curl,CURLOPT_URL,$URL);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_HEADER, true);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

   $respuesta = curl_exec($curl);
   #echo $respuesta;
   $respuesta = explode("\n\r\n", $respuesta);
   $headers = $respuesta[0];
   #echo $headers;
   $body = $respuesta[1];
   curl_close($curl);

   if (str_contains($headers,'201 Created')){
    return "Mensaje Creado Correctamente";

    }
    else{
      echo $headers;
      return "Mensaje NO Creado ";
    }
   

 }


?>