<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../include/phpmailer/Exception.php';
    require '../../include/phpmailer/PHPMailer.php';
    require '../../include/phpmailer/SMTP.php';

    require_once '../../utilities/load-env.php';
    require_once '../../utilities/response.php';
    require_once '../../utilities/template-builder.php';
    require_once '../../utilities/transpiler.php';

    $domain = parse_url($_SERVER['HTTP_HOST']);
    $_POST['domain'] = $domain['path'];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
        // var_dump($_POST);

        foreach ($required_keys as $value) {
                if ( !isset($_POST[$value]) || strlen($_POST[$value]) <= 2 ) {
                        response(400, $label_keys[$value] . ' is required.', $_POST);
                        die();
                    }
                }

        foreach ($_POST as $key => $value) {
            $post = htmlspecialchars($value);
            // verifications
            if($key == "name" && strlen($post) < 20 && strlen($post) > 2) {
                continue;
            }
            elseif($key == "email" && strlen($post) > 5 && strlen($post) < 50 && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $post)) {
                continue; 
            }
            elseif($key == "message" && strlen($post) > 10 && strlen($post) < 500) {
                continue;   
            }
            elseif($key == "phone" && strlen($post) > 6 && is_numeric($post)) {
                continue;
            }
            elseif($key == "company" && strlen($post) > 2 && strlen($post) < 50) {
                continue;
            }
            elseif($key == "domain" && strlen($post) > 2 && strlen($post) < 50) {
                continue;
            }
            else {
                response(400,  $label_keys[$key] . ' validation failed.', $post);
                die();
            }
        }
            
        $email = build(getenv('TEMPLATE_NAME'), $_POST);
        $mail = new PHPMailer(true);

        try {
            //Recipients
            $mail->setFrom(getenv('SENDER_EMAIL'), getenv('SENDER_NAME'));
            $mail->addAddress(getenv('RECIEVER_EMAIL'), getenv('RECIEVER_NAME'));
            
            //Content
            $mail->isHTML(true);
            $mail->Subject = 'New message from your website';
            $mail->Body    = $email['html'];
            $mail->AltBody = $email['text'];

            //Sending Message
            $mail->send();
            response(200, 'Message has been sent.', $_POST);
        } catch (Exception $e) {
            response(500, 'Message could not be sent.', $mail->ErrorInfo);
        }
    } else {
        response(400, 'Message could not be sent.', "Only POST Method is supported.");
    }