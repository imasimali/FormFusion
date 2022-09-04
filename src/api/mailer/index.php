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

    $required = array("name", "email", "message");
    $fields = array("name", "email", "message", "phone", "company");
    
    foreach ($required as $value) {
            if ( !isset($_POST[$value]) || strlen($_POST[$value]) <= 2 ) {
                    response(400, $label_keys[$value] . ' is required.');
                    die();
                }
            }

    if(isset($_POST)) {
        // pour savoir si les champs valides - array that checks if the inputs are valid
        $valid = array();

        // for each parameter
        // var_dump($_POST);
        foreach ($_POST as $key => $value) {
            // the key of each parameter
            $postKey = $key;
            // if the parameter exists in $_POST
            // le champ aka parameter de post
            $post = $value;
            // security
            $post = htmlspecialchars($post);
            // verifications
            if($postKey == "name" && strlen($post) < 20 && strlen($post) > 2) {
                $valid["name"] = "valid";
            }
            elseif($postKey == "email" && strlen($post) > 5 && strlen($post) < 50 && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $post)) {
                $valid["email"] = "valid"; 
            }
            elseif($postKey == "message" && strlen($post) > 10 && strlen($post) < 500) {
                $valid["message"] = "valid";   
            }
            elseif($postKey == "phone" && strlen($post) > 6 && is_numeric($post)) {
                $valid["phone"] = "valid";
            }
            elseif($postKey == "company" && strlen($post) > 2 && strlen($post) < 50) {
                $valid["company"] = "valid";
            }
            else {
                // si ca matche pas avec les patterns de chaque if - if the current input does not match with it corresponding regex
                // if(!array_keys($valid, $postKey))
                response(400,  $label_keys[$postKey] . ' validation failed.', $post);
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

            $mail->send();
            response(200, 'Message has been sent.', $_POST);
        } catch (Exception $e) {
            response(500, 'Message could not be sent.', $mail->ErrorInfo);
        }

    } else {
        response(400, 'Message could not be sent.', "Not Post Method");
    }