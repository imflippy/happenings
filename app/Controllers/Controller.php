<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\Admin;
use app\Models\News;

class Controller {

    protected function loadView($view, $data = null) {
        require_once "app/Views/fixed/head.php"; // head tag
        require_once "app/Views/fixed/header.php"; // navigation and title
        require_once "app/Views/pages/". $view . ".php"; // view je promenjiva kojij se prosledjuje strana koja se ucitava
        require_once "app/Views/fixed/sidebar.php"; //right-sidebar loaded 4 every page
        require_once "app/Views/fixed/footer.php"; //footer
    }

    protected function redirect($page) {
        header("Location:" .$page);
    }

    protected function json($data = null, $statucCode = 200) {
        header("Content-type: application/json");
        http_response_code($statucCode);
        echo json_encode($data);
    }

    protected function errorLog($function, $message) {
        $modelAdmin = new Admin(Database::instance());
        $modelAdmin->error_log($function, $message, date("Y-m-d H-i-s", time()));
        http_response_code(500);
        $this->redirect("index.php?page=404");
    }
    protected function errorLogNoRedict($function, $message) {
        $modelAdmin = new Admin(Database::instance());
        $modelAdmin->error_log($function, $message, date("Y-m-d H-i-s", time()));
        http_response_code(500);
    }

    protected function adminLog($function, $message, $code) {
        $modelAdmin = new Admin(Database::instance());
        $modelAdmin->admin_success($function, $message, $_SESSION['user']->id_user, date("Y-m-d H-i-s", time()));
        http_response_code($code);
        $this->redirect("index.php?page=index");
    }
    protected function commentLog($message, $action, $code) {
        http_response_code($code);
        $modelAdmin = new Admin(Database::instance());
        $modelAdmin->commentLog($message, $action, $_SESSION['user']->id_user, date("Y-m-d H-i-s", time()));
    }

    protected function getLogInfo() {
        $modelAdmin = new Admin(Database::instance());
        $page = explode(".php", $_SERVER['REQUEST_URI']);
        if($page[1] == "") {
            $page[1] = "home";
        }
        $ipAdress = $_SERVER['REMOTE_ADDR'];
        if(isset($_SESSION['user'])){
            $idUser = $_SESSION['user']->id_user;
        } else {
            $idUser = 0;
        }
        $time = date("Y-m-d H-i-s", time());
        $modelAdmin->getLogInfo($page[1], $ipAdress, $idUser, $time);

    }


}