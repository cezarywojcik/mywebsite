<?php

$output = "";
$name = "";
$email = "";
$message = "";

function error($error) {
    $result = "";
    $result .= '<div class="error">Some errors were found while processing your request:<br>';
    $result .= $error;
    $result .= "Please go back and fix any errors.<br><br></div>";
    return $result;
}

if(isset($_POST['email'])) {
    $email_to = "cezarywojcik92@gmail.com";

    if(!isset($_POST['name']) || !isset($_POST['email']) ||
        !isset($_POST['message'])) {
        error('-There appears to be a problem with the form you submitted.');
    } else {
        $name = htmlspecialchars($_POST['name']);
        $email_subject = "CW.com - from " . $name . "";
        $email= $_POST['email'];
        $message = htmlspecialchars($_POST['message']);

        $error_message = "";

        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
        if(!preg_match($email_exp, $email)) {
            $error_message .= '-The Email Address you entered does not appear to be valid.<br>';
        }
        if (strlen($name) == 0) {
            $error_message .= '-The Name field is empty. <br>';
        }
        if (strlen($message) == 0) {
            $error_message .= '-The Message field is empty. <br>';
        }
        if(strlen($error_message) > 0) {
            $output = error($error_message);
        } else {
            $email_message = "";
            $email_message .= "Name: ". $name . "\n";
            $email_message .= "Email: ". $email . "\n";
            $email_message .= "IP: ". $_SERVER['REMOTE_ADDR'] . "\n";
            $email_message .= "Message: " . $message . "\n";

            $headers = 'From: '.$email."\r\n".
                'Reply-To: '.$email."\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($email_to, $email_subject, $email_message, $headers);
            $output = '<div class="success">Sent Succesfully!</div><br>';
        }
    }
}
