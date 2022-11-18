<?php
#Autor: Fran Oliveira
#e-mail:foliveirag@gmail.com
#PRAC1 Fundamentos de Ciberseguridad
# OAuth

header('Access-Control-Allow-Origin: *'); 
include("API.php");
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
  $name="";
  $surname="";
  $imagen="";
  $mifecha="";
  $client_id="77vangioyjigoo";
  $client_secret="vPEKLnbwfefCHaQb";

  # Comprobar que recibimos un msg de no autorizado si intentamos hacer consultas con un token  NO valido
  if (isset($_GET['NO_VALIDO'])) {
    $token="";
    $URL="https://api.linkedin.com/v2/me?";
    $URL=$URL."oauth2_access_token=".$token ;#."&redirect_uri=http://www.arnal.com";
    $profile=getUserProfile($URL);

  }
# recojer los parámetros de la URL 
      if (isset($_GET['code'])) {
          $code=$_GET['code'];
          $state=isset($_GET['state']);

          $URL="https://www.linkedin.com/oauth/v2/accessToken";
          $data="&grant_type=authorization_code&code=".$code."&redirect_uri=http://www.arnal.com&client_id=".$client_id."&client_secret=".$client_secret;

          #Llamamos a la función para conseguri un Token
          $body=GetToken($code,$URL,$data);
          $token=$body["access_token"];
          $expires=$body["expires_in"];
          
          $mifecha = new DateTime();
          #echo " Mi Fecha ".$mifecha;
          $mifecha->modify('+'.$expires.' second');
          #echo $mifecha->format('d-m-Y H:i:s');

          

          #Llamamos a la api para conseguir la información del usuario
          $URL="https://api.linkedin.com/v2/me?";
          $URL=$URL."oauth2_access_token=".$token ;#."&redirect_uri=http://www.arnal.com";
          $profile=getUserProfile($URL);
          #echo "\n\n\n";
          #echo $profile;
          $profile=json_decode($profile,True);
          #echo gettype($profile);
          $name= $profile["localizedFirstName"];
          $surname=$profile["localizedLastName"];



          #Llamamos a la api para conseguir la imagen del usuario
          $URL="https://api.linkedin.com/v2/me?projection=(profilePicture(displayImage~:playableStreams))";
          $URL=$URL."&oauth2_access_token=".$token ;#."&redirect_uri=http://www.arnal.com";
          $profile=getUserProfile($URL);
          echo "\n\n\n";
          $profile=json_decode($profile,True);
          #echo gettype($profile);
          $imagen=(($profile["profilePicture"]["displayImage~"]["elements"][0]["identifiers"][0]["identifier"]));
          



      } else {

echo '<div class="contenedor">
<div class="hijo">

  <h1 id="msg" style="visibility:visible"> Pulsa para acceder a Linkedin</h1>
  <a id="Foto_enlace" style="visibility:visible"  href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=77vangioyjigoo&redirect_uri=http://www.arnal.com&
state=FundamentosCibeseguridad&scope=r_emailaddress r_liteprofile">
<img src="linkedin.png" width="200" height="255" class="center"  />
</a>
</div>
</div>';
    
      }   
      ?>
      <?php if ($name!=""):?> 
      <table style="width:50%" class="center">
  
          <tr>
            <th rowspan="5">
              <?php echo '<img src='.$imagen.' alt="Fundamentos Ciberseguridad" width="100" height="120">' ?>
             </th>
            <td><?php echo $name; ?></td>
          </tr>
          <tr>
            <td><?php echo $surname; ?></td>
          </tr>
          <tr>
            <th>Fecha de expiración del token</th>
            <td><?php echo $mifecha->format('d-m-Y H:i:s'); ?></td>
          </tr>
          <tr>
            <th><a href="http://www.arnal.com/index.php?NO_VALIDO=1">Prueba un Token NO_VALIDO</a></th>
            <td></td>
          </tr>
          <tr>
            <th><a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=77vangioyjigoo&redirect_uri=http://www.arnal.com/msg&
state=FundamentosCibeseguridad&scope=r_emailaddress r_liteprofile w_member_social">Enviar Msg</a></th>
            <td></td>
          </tr>
        </table>
        
        </table style ="width:50%" class="center">
        <tr>
            
            <td></td>
        </tr>
          

      </table>
      <?php endif ?>      
  </body>
</html>
