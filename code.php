<?php
session_start();
include('dbcon.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token)
{
 
  // print_r("test");
  // die;

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
  // $mail->Password   = 'Program@18';                     
  // $mail->SMTPSecure = 'tls';   
  // $mail->Port = 587;                              

  //Recipients
  $mail->setFrom('programmergithub18@gmail.com', '$name');
  $mail->addAddress($email);
  $mail->isHTML(true);  
  $mail->Subject = "Email verification from funda of web IT";                                                 
  $email_template = "
  
  <h2>You  have Registered with Funda of web IT </h2>
  <h5>Verify  your email address to login with the below given link</h5> 
  <br/></br>
  <a href = 'http://localhost/Email%20Verification/verify-email.php?token=$verify_token'>Click Me</a>
  ";
  
  $mail->Body    = $email_template;
  $mail->send();
  
  
  // echo 'Message has been sent';
}
  if(isset($_POST['register_btn']))
  {
    
    // var_dump($_POST);
   $name = $_POST['name'];
   $phone = $_POST['phone'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $verify_token = md5(rand());
   

  //Email Exsits or Not
  $check_email_query = "SELECT email  FROM users WHERE email='$email' LIMIT 1";
  $check_email_query_run = mysqli_query($con, $check_email_query);
  if(mysqli_num_rows( $check_email_query_run) > 0)
  {
  $_SESSION['status'] = "Email id already Exists";
  header("Location: register.php");
  }
  else
  {
     $query = "INSERT INTO users (name, phone, email, password, verify_token) VALUES('$name', '$phone', '$email', '$password', '$verify_token')";  //Insert user /Register User Data
     $query_run = mysqli_query($con, $query);
     if($query_run)
     {  

        sendemail_verify("$name","$email","$verify_token");

        $_SESSION['status'] = "Registration Successfull.! Please verify your email address";
        header("Location: register.php"); 
     }else
     {
       $_SESSION['status'] = "Registration Failed";
       header("Location: register.php"); 
     }

  }
  }
?> 