<?php
    require_once 'load-env.php';

function build($name, $data) {
    $html = file_get_contents(__DIR__ . '/../templates/' . $name . '/mail.html');
    $txt = file_get_contents(__DIR__ . '/../templates/' . $name . '/mail.txt');
    
    $data['TEMPLATE_LOGO_URL'] = getenv('TEMPLATE_LOGO_URL');
    $data['TEMPLATE_HEADER_BACKGROUND_COLOR'] = getenv('TEMPLATE_HEADER_BACKGROUND_COLOR');
    $data['TEMPLATE_FOOTER_BACKGROUND_COLOR'] = getenv('TEMPLATE_FOOTER_BACKGROUND_COLOR');

    foreach ($data as $key => $value) {
        $html = str_replace('%%' . strtoupper($key) . '%%', $value, $html);
        $txt = str_replace('%%' . strtoupper($key) . '%%', $value, $txt);
    }

    return [
        'html' => $html,
        'text' => $txt,
    ];
}