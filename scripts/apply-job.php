<?php 

$_subject = "Others";
$name = trim($_POST['fname']);
$email = trim($_POST['email']);
$phone = (isset($_POST['phone']))?trim($_POST['phone']):'';
$position = (isset($_POST['position']) && !empty($_POST['position'])) ? $_POST['position'] : "";
$experience = (isset($_POST['experience']) && !empty($_POST['experience'])) ? $_POST['experience'] : "";
$hear_about_us = (isset($_POST['hear_about_us']) && !empty($_POST['hear_about_us'])) ? $_POST['hear_about_us'] : "";


$cover_letter = (isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) ? $_FILES['files']['name'][0] : "";
$resume = (isset($_FILES['files']['name'][1]) && !empty($_FILES['files']['name'][1])) ? $_FILES['files']['name'][1] : "";
$picture = (isset($_FILES['files']['name'][2]) && !empty($_FILES['files']['name'][2])) ? $_FILES['files']['name'][2] : "";

$cover_ext = pathinfo($cover_letter, PATHINFO_EXTENSION);
$resume_ext = pathinfo($resume, PATHINFO_EXTENSION);
$picture_ext = pathinfo($picture, PATHINFO_EXTENSION);

$docs_allowed =  array('pdf','doc' ,'docx');
$picture_allowed =  array('jpg','jpeg','png','gif');

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
} else if ($phone == "") {
    if(isset($_POST['phone'])){
        $msg['err'] = "\n Phone number can not be empty!";
        $msg['field'] = "phone";
        $msg['code'] = FALSE;
    }
} else if (!preg_match("/^[0-9\\-\\+]{4,17}$/", trim($phone))) {
    $msg['err'] = "\n Please put a valid phone number!";
    $msg['field'] = "phone";
    $msg['code'] = FALSE;

} else if ($position == "") {
    $msg['err'] = "\n Position can not be empty!";
    $msg['field'] = "position";
    $msg['code'] = FALSE;
} else if ($experience == "") {
    $msg['err'] = "\n Experience can not be empty!";
    $msg['field'] = "experience";
    $msg['code'] = FALSE;
} else if ($hear_about_us == "") {
    $msg['err'] = "\n Hear About Us can not be empty!";
    $msg['field'] = "hear_about_us";
    $msg['code'] = FALSE;
} else if($cover_letter==""){
    $msg['err'] = "\n Cover letter can not be empty!";
    $msg['field'] = "comment-InputFile-1";
    $msg['code'] = FALSE;
} else if(!in_array($cover_ext, $docs_allowed)){
    $msg['err'] = "\n Only support doc,dcox,pdf format!";
    $msg['field'] = "comment-InputFile-1";
    $msg['code'] = FALSE;
} else if($resume==""){
    $msg['err'] = "\n Resume can not be empty!";
    $msg['field'] = "comment-InputFile-2";
    $msg['code'] = FALSE;
} else if(!in_array($resume_ext, $docs_allowed)){
    $msg['err'] = "\n Only support doc,dcox,pdf format!";
    $msg['field'] = "comment-InputFile-2";
    $msg['code'] = FALSE;
} else if($picture==""){
    $msg['err'] = "\n Application picture can not be empty!";
    $msg['field'] = "comment-InputFile-3";
    $msg['code'] = FALSE;
} else if(!in_array($picture_ext, $picture_allowed)){
    $msg['err'] = "\n Only support jpg,jpeg,png,gif format!";
    $msg['field'] = "comment-InputFile-3";
    $msg['code'] = FALSE;
} else {

    $from_email         = 'contact@example.com'; //from mail, it is mandatory with some hosts
    $recipient_email    = 'user@example.com'; //recipient email (most cases it is your personal email)
    $subject = 'Tropica SEO: Apply fo a Job';

    $boundary = md5(time());
    
    //header
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= 'From:  Tropica SEO Agency <contact@example.com>' . "\r\n";
    $headers .= 'cc: user@example.com' . "\r\n";
    $headers .= 'bcc: user@example.com' . "\r\n";

    // message html
    $message = '<html><head></head><body>';
    $message .= '<p>Name: ' . $name . '</p>';
    $message .= '<p>Email: ' . $email . '</p>';
    $message .= '<p>Phone: ' . $phone . '</p>';
    $message .= '<p>Position: ' . $position . '</p>';
    $message .= '<p>Experience: ' . $experience . '</p>';
    $message .= '<p>How did you hear about us?: ' . $hear_about_us . '</p>';
    $message .= '</body></html>';

	$body = "";
	$body .= "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n"; //optional defaults to 7bit
	$body .= "$message\r\n";
    
    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
    
    //plain text 
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
    
    //attachment
	for($x=0;$x<count($_FILES['files']['name']);$x++){
        //echo "<pre>";print_r($_FILES);
		$file_tmp_name    = $_FILES['files']['tmp_name'][$x];
        $file_name        = $_FILES['files']['name'][$x];
        $file_size        = $_FILES['files']['size'][$x];
        $file_type        = $_FILES['files']['type'][$x];
        $file_error       = $_FILES['files']['error'][$x];

        //read from the uploaded file & base64_encode content for the mail
        $handle = fopen($file_tmp_name, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));
        $body .= "--$boundary\r\n";
        $body .="Content-Type: $file_type; name=".$file_name."\r\n";
        $body .="Content-Disposition: attachment; filename=".$file_name."\r\n";
        $body .="Content-Transfer-Encoding: base64\r\n";
        $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 
        $body .= $encoded_content; 
	}
    @mail($recipient_email, $subject, $body, $headers, '-f user@example.com'); 
    $msg['success'] = "\n Email has been sent successfully.";
    $msg['code'] = TRUE;
}
echo json_encode($msg);
?>