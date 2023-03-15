<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/attach_mailer/attach_mailer_class.php");

$test = new attach_mailer($name = "Olaf", $from = "your@mail.com", $to = "he@gmail.com", $cc = "", $bcc = "", $subject = "Test text email with attachments");
$test->text_body = "...Some body text\n\n the admin";
$test->add_attach_file("image.gif");
$test->add_attach_file("ip2nation.zip"); 
$test->process_mail();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Attachment Mailer example script</title>
</head>

<body>
<h2>Attachment Mailer example</h2>
<p>This is a simple example of how to use this class. This example sends a text type e-mail with multiple attachements.</p>
<p style="color:#FF0000;"><?php echo $test->get_msg_str(); ?></p>
</body>
</html>
