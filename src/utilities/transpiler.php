<?php
    require_once 'load-env.php';

    $dataPairs = explode(',', getenv('LABEL_KEYS'));
    $label_keys = [];

    foreach ($dataPairs as $value) {
        $temp = explode(':', $value);
        $label_keys[$temp[0]] = $temp[1];
    }