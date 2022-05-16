<?php
/**
 * Created by PhpStorm.
 * User: Laravel
 * Date: 21-04-2022
 * Time: AM 11:45
 */

namespace App\Api;


class Propertyapi
{
     public function property()
     {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://mgtspe.dreamplesk.com/public/api/getfeatured',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));

      $response = curl_exec($curl);

              curl_close($curl);
         return json_decode($response,true);

     }
}
