<?php
session_start();
include('dbcon.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
function send_password_reset($get_name, $get_email, $token)
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;
    $mail->isSMTP();   
    $mail->SMTPAuth = true;                                        
    $mail->Host       = 'sandbox.smtp.mailtrap.io';                     
    $mail->Username   = 'c187f0491a1ed0';       
    $mail->Password   = '0423603ef26217'; 
    $mail->Port       = 587;  
    $mail->SMTPSecure = 'tls';   
  
    // $mail->Host = 'smtp.gmail.com';
    // $mail->Username   = 'programmergithub18@gmail.com';       
    // $mail->Password   = '';                     
    // $mail->SMTPSecure = 'tls';   
    // $mail->Port = 587;                              
  
    //Recipients
    $mail->setFrom('programmergithub18@gmail.com', "$get_name");
    $mail->addAddress($get_email);
    $mail->isHTML(true);  
    $mail->Subject = "Reset password notification";                                                 
    $email_template = "
    
    <h2>Hello</h2>
    <h5>You are receiving email because we received a password reset notification for your account</h5> 
    <br/></br>
    <a href = 'http://localhost/Email%20Verification/password-reset.php?token=$token&email=$get_email'>Click Me</a>
    ";
    
    $mail->Body    = $email_template;
    $mail->send();
}
if(isset($_POST['password_reset_link']))
{
$email = mysqli_real_escape_string($con, $_POST['email']);
$token = md5(rand());
$check_email = "SELECT email FROM users WHERE email ='$email' LIMIT 1";
$check_email_run =mysqli_query($con,$check_email);
if(mysqli_num_rows($check_email_run) > 0)
{
$row = mysqli_fetch_array($check_email_run);
$get_name = $row['name'];
$get_email = $row['email'];

$update_token = "UPDATE users SET verify_token='$token' WHERE email = '$get_email' LIMIT 1";
$update_token_run = mysqli_query($con,$update_token);
if($update_token_run)
{
send_password_reset($get_name,$get_email,$token);
$_SESSION['status'] = "We E-mailed you a password reset link";
header("Location: password-reset.php");
exit(0);
}
else
{
    $_SESSION['status'] = "Something went wrong #1";
    header("Location: password-reset.php");
    exit(0);
}
}
}
else
{
    $_SESSION['status'] = "No Email Found";
    header("Location: password-reset.php");
    exit(0);
}
?>