<?php 
#Autor: Fran Oliveira
#e-mail:foliveirag@gmail.com
#PRAC1 Fundamentos de Ciberseguridad
# OAuth

header('Access-Control-Allow-Origin: *'); 
include("../API.php");


?>

<!DOCTYPE html>

<html>
<head>
  <style type="text/css">
.contenedor {
  
  justify-content: center;
  align-items: center;
}

.hijo {
  position: absolute;
  top: 35%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}
<style>
table, th, td {
  border: 0px solid black;
  border-collapse: collapse;
}

  </style>
  
</head>
  <body>
  <?php
  $code="";
  $state ="";
  $token ="";
  $expires="";
  $client_id="77vangioyjigoo";
  $client_secret="vPEKLnbwfefCHaQb";
  
# comprueba que existe un code y recoje los parámetros de la URL
      if (isset($_GET['code'])) {
          
          $code=$_GET['code'];
          $state=isset($_GET['state']);

          $URL="https://www.linkedin.com/oauth/v2/accessToken";
          $data="&grant_type=authorization_code&code=".$code."&redirect_uri=http://www.arnal.com/msg&client_id=".$client_id."&client_secret=".$client_secret;
         
          #Llamada a la función para conseguri un Token
          
          $body=GetToken($code,$URL,$data);
          $token=$body["access_token"];   
          
      } 

     # comprueba que se ha enviado un msg y recoje los parámetros 
      if (isset($_POST["msg"])){
        #echo "check post";
        $msg=$_POST["msg"];
        $token=$_POST["token"];
        #echo $token;

        #Llamada a la api para conseguir el id del usuario
        $URL="https://api.linkedin.com/v2/me?";
        $URL=$URL."oauth2_access_token=".$token ;
        $profile=getUserProfile($URL);
        $profile=json_decode($profile,True);
        $id= $profile["id"];

        #Formatea el cuerpo del body e incluye el msg a enviar
        $data=$data.'{
          "author": "urn:li:person:'.$id.'",
          "lifecycleState": "PUBLISHED",
          "specificContent": {
              "com.linkedin.ugc.ShareContent": {
                  "shareCommentary": {
                      "text": "'.$msg.'"
                  },
                  "shareMediaCategory": "NONE"
              }
          },
          "visibility": {
              "com.linkedin.ugc.MemberNetworkVisibility": "PUBLIC"
          }
      }';
      
      #Llamada a la api para publicar contenido, resuldado corecto devuelve '201 Created'

      $URL="https://api.linkedin.com/v2/ugcPosts?";
      $URL=$URL."oauth2_access_token=".$token ;
        $resultado=Publish($URL,$data);

      }
      if (isset($_GET['error'])){

        header("Location: http://www.arnal.com");
        die();
      }

?>

<div class="contenedor">
  <div class="hijo">
    
  <h1 id="msg" style="visibility:visible"> Enviar un Mensage</h1>
  <a id="Foto_enlace" style="visibility:visible"  href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=77vangioyjigoo&redirect_uri=http://www.arnal.com&
state=FundamentosCibeseguridad&scope=r_emailaddress r_liteprofile">
<img src="../linkedin.png" width="200" height="255" class="center"  /></a>

  <form action="index.php" method="post">
    Escribe tu msg: <input type="text" name="msg" style="WIDTH: 228px; HEIGHT: 150px"  >
    <input type="hidden" name="token" value=<?php echo $token; ?>>
    <input type="submit" value="Enviar">
</form>
<p>
<?php echo $resultado;?>
    


</div>
</div>
    
      

     
          
  </body>
</html>
