<?php

function response($status,$status_message,$data = null) {
    header("HTTP/1.1 ".$status);
    header('Content-Type: application/json');

    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
