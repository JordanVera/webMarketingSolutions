<?php

$multicheck = array();
$_subject = "Others";
$name = trim($_POST['fname']);
$email = trim($_POST['email']);
$phone = (isset($_POST['phone']))?trim($_POST['phone']):'';
$website = (isset($_POST['website']))?$_POST['website']:'';
$_checkbox = (isset($_POST['multicheck']) && !empty($_POST['multicheck'])) ? $_POST['multicheck'] : $multicheck;
$_checkboxCount = count($_checkbox);


if($_checkboxCount>0){
    $_subject = implode (", ", array_filter($_checkbox));
}
$_message = trim($_POST['message']);


if ($name == "") {
    $msg['err'] = "\n Full name can not be empty!";
    $msg['field'] = "fname";
    $msg['code'] = FALSE;
} else if ($email == "") {
    $msg['err'] = "\n Email can not be empty!";
    $msg['field'] = "Email";
    $msg['field'] = "email";
    $msg['code'] = FALSE;
} else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $msg['err'] = "\n Please put a valid email address!";
    $msg['field'] = "email";
    $msg['code'] = FALSE;
} else if ($phone == "" && isset($_POST['phone'])) {   
    if(isset($_POST['phone'])){
        $msg['err'] = "\n Phone number can not be empty!";
        $msg['field'] = "phone";
        $msg['code'] = FALSE;
    }
} else if ((!preg_match("/^[0-9\\-\\+]{4,17}$/", trim($phone) )) && isset($_POST['phone']) ) {
    $msg['err'] = "\n Please put a valid phone number!";
    $msg['field'] = "phone";
    $msg['code'] = FALSE;

} else if ($_message == "") {
    $msg['err'] = "\n Message can not be empty!";
    $msg['field'] = "message";
    $msg['code'] = FALSE;
} else {
    $to = 'verawebdevelopment@gmail.com';
    $subject = 'Contact Query';
    $message = '<html><head></head><body>';   
    
    if(isset($_subject) && !empty($_subject)){
        $message .= '<p>Subject: ' . $_subject . '</p>';
    }
    $message .= '<p>Name: ' . $name . '</p>';
    $message .= '<p>Email: ' . $email . '</p>';
    $message .= '<p>Phone: ' . $phone . '</p>';
    $message .= '<p>Message: ' . $_message . '</p>';
    $message .= '<p>Website: ' . $website . '</p>';
    $message .= '</body></html>';
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From:  Tropica SEO Agency <test@example.com>' . "\r\n";
    $headers .= 'cc: user@example.com' . "\r\n";
    $headers .= 'bcc: user@example.com' . "\r\n";
    
    mail($to, $subject, $message, $headers, '-f user@example.com');

    $msg['success'] = "\n Email has been sent successfully.";
    $msg['code'] = TRUE;

 

}
echo json_encode($msg);