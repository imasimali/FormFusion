<?php
    require_once 'load-env.php';

    function build($name, $data) {
        $html = file_get_contents(__DIR__ . '/../templates/' . $name . '/mail.html');
        $txt = file_get_contents(__DIR__ . '/../templates/' . $name . '/mail.txt');
        $block = file_get_contents(__DIR__ . '/../templates/' . $name . '/block.html');
        
        // Load the HTML document
        $doc = new DOMDocument;
        $doc->loadHTML( $html );
        
        // Get the parent node where you want the insertion to occur
        $parent = $doc->getElementById('input-fileds');
        
        $innersection = new DOMDocument();
        $innersection->loadHTML( $block );

        $xpath = new DOMXPath($innersection);
        $inputvalue = $xpath->query('/html/body/div/div/div/div/div/div/div[1]/div/div/p/span')->item(0);
        $inputkey = $xpath->query('/html/body/div/div/div/div/div/div/div[2]/div/div/p/span')->item(0);
        
        foreach ($data as $key => $value) {
            $inputkey->nodeValue = strtoupper($key);
            $inputvalue->nodeValue = $value;
            $innersection->saveHTML();
            $parent->appendChild( $doc->importNode($innersection->documentElement, TRUE) );
        }

        $html = $doc->saveHTML();

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