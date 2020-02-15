<?php

// $json = '{"image_url[1]":"http://temp-images.ws.pho.to/d98d8299df448e1db590186e028c4819258ad54b.jpeg", "template_name":"1003506", "animated":"1"}';
//     if ($curl = curl_init()) {
//         curl_setopt($curl, CURLOPT_URL, 'http://api-soft.photolab.me/template_process.php');
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($curl, CURLOPT_POST, true);
//         curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
//         $response = curl_exec($curl);
//         curl_close($curl);
//     }


    // $json = '{"image_url[1]":"http://temp-images.ws.pho.to/d98d8299df448e1db590186e028c4819258ad54b.jpeg", "template_name":"1003506", "animated":"1"}';
    // $options = ['method' => 'POST',
    //     'header' => 'Content-type:application/x-www-form-urlencoded',
    //     'content' => $json];
    // $context = stream_context_create($options);
    // $response = file_get_contents('http://api-soft.photolab.me/template_process.php', false, $context);


    // //Формируем JSON
    // $request_data = array('image_url[1]' => 'http://temp-images.ws.pho.to/d98d8299df448e1db590186e028c4819258ad54b.jpeg', 'template_name' => '1003506', 'animated' => '1');
    // $json = json_encode($request_data);

    // //Настраиваем cURL
    // $ch = curl_init('http://api-soft.photolab.me/template_process.php');
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // //Получаем данные
    // $response = curl_exec($ch);


    $data = ["image_url[1]" => "http://temp-images.ws.pho.to/d98d8299df448e1db590186e028c4819258ad54b.jpeg", "template_name" => 1003506, "animated" => 1];
    $data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
    $curl = curl_init('http://api-soft.photolab.me/template_process.php');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    // Принимаем в виде массива. (false - в виде объекта)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($curl);
    curl_close($curl);
    echo '<pre>';
    print_r($result);

?>