<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\User;

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';



class RegisterController extends FrontendController {

    public function __construct()
    {
        parent::__construct();
    }

    public function registerPage() {
        $this->loadView('register', $this->data); // forma za registraciju
        $this->getLogInfo();
    }
    public function registerConfirmPage() {
        $this->loadView("confirmRegister", $this->data); //strana na kojoj se nalazi potvrda o registraciji
        $this->getLogInfo();

        $model = new User(Database::instance());
        $model->ConfirmRegister($_GET['token']);
    }

    public function register() {
        if(isset($_POST['btnRegister'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];
            $token = sha1(rand()) . time();
            $created_at = date("Y-m-d H-i-s", time());

            $errors = []; //array za greske

            $regexPassword ="/^(?=.*\d).{6,}$/"; // mora biti veca od 6 i mora imati barem jedan broj

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Mail - Wrong Format";
            }
            if(!preg_match($regexPassword, $password)) {
                $errors[] = "Password - Wrong Format";
            }

            if(count($errors) > 0) {
                $this->json($errors, 422);
                exit;
            }
            try {
                $model = new User(Database::instance());
                $password = md5($password);
                $model->AddUser($email, $password, $token, $created_at);
                http_response_code(201); //json f-ja treba da se stavi
                //Mailer
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';

                    $mail->SMTPAuth = true;
                    $mail->Username = 'happenings1566@gmail.com';
                    $mail->Password = 'happenings435';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('happenings1566@gmail.com', 'Happenings Email Verification Required');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    // Set email format to HTML
                    $mail->Subject = 'Activation';
                    $mail->Body    = "Activate your account by clicking on: <a href='http://localhost:8080/Happenings/index.php?page=confirmRegister&token={$token}'>This link</a>";
//                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                } catch (Exception $e) {
                    $this->errorLogNoRedict("register()91", $mail->ErrorInfo);
                }

            } catch (\PDOException $ex) {
                $this->errorLogNoRedict("register()95", $ex->getMessage());

            }
        } else { //endif isset
            $this->json(null, 403);
        }


    }
}