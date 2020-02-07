<?php

namespace app\Controllers;


use app\Config\Database;
use app\Models\Admin;
use app\Models\User;

class LoginController extends FrontendController {

    public function __construct()
    {
        parent::__construct();
    }

    public function loginPage() {
        $this->loadView('login', $this->data);
        $this->getLogInfo();
    }

    public function login() {
        if(isset($_POST['btnLogin'])) {


            $email = $_POST['email'];
            $password = $_POST['password'];

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
                $this->redirect("index.php?page=login");
                exit;
            }

            try {
                $password = md5($password);

                $model = new User(Database::instance());
                $user = $model->login($email, $password);
                if($user == null) {
                    $this->redirect("index.php?page=login");
                    exit;
                }
                $_SESSION['user'] = $user;
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->loginAdd($_SERVER['REMOTE_ADDR'], $_SESSION['user']->id_user);
                $this->redirect("index.php?page=home");

            } catch (\PDOException $ex) {
                $this->errorLog("login()", $ex->getMessage());
            }
        } else {
            $this->json("Button no action - login", 403);
            $this->redirect("index.php?page=login");
        }

    }
    public function logout() {
        $modelAdmin = new Admin(Database::instance());
        $modelAdmin->logginRemove($_SESSION['user']->id_user);
        unset($_SESSION['user']);
        $this->redirect("index.php");
    }


}