<?php

function jwt_request($token, $post, $method) 
{

   $apiUrl = 'http://localhost/kipuapi/public';
   $authorization = "Authorization: Bearer ".$token;

   switch($method) {
      case 'POST':
         header('Content-Type: application/json');
         $ch = curl_init($apiUrl);
         $post = json_encode($post);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_POST, 1); 
         curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
         $result = curl_exec($ch);
         curl_close($ch); 
      break;
      case 'GET':
         $curl = curl_init();

         curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => array('Content-Type: application/json' , $authorization ),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $apiUrl.'/reservation/3'
         ]);

         $result = curl_exec($curl); 
         var_dump($result);
         curl_close($curl); // Close the cURL connection

      break;
   }
   return $result; // Return the received data
}
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2tpcHVhcGlcL3B1YmxpY1wvYXV0aFwvbG9naW4iLCJpYXQiOjE1NTk1NDI2OTEsImV4cCI6MTU1OTU0NjI5MSwibmJmIjoxNTU5NTQyNjkxLCJqdGkiOiJIWjNQeGhLd2NoZjRIdmRRIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.Ld0-jlT_m5oEBwNGK20SetmtqkMiUPmeoY-xNzVIy7I';
/*echo '<pre>';
var_dump(json_decode(jwt_request($token, '', 'GET')));
echo '</pre>';*/

?>
