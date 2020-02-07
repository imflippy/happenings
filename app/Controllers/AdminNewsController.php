<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\Admin;
use app\Models\News;

class AdminNewsController extends FrontendController {
    public function __construct()
    {
        parent::__construct();
    }

    public function addNewsPage() {
        if($_SESSION['user']->id_role == 2 || !isset($_SESSION['user'])) {
            $this->redirect("index.php?page=404");
        }

        $this->loadView('addNews', $this->data);
        $this->getLogInfo();
    }

    public function users() {
        if($_SESSION['user']->id_role == 2 || !isset($_SESSION['user'])) {
            $this->redirect("index.php?page=404");
        }
        $modelAdmin = new Admin(Database::instance());
        $roles = $modelAdmin->getRoles();
        $this->data['roles'] = $roles;

        $this->loadView('users', $this->data);
        $this->getLogInfo();
    }
    public function logPage() {
        if($_SESSION['user']->id_role == 2 || !isset($_SESSION['user'])) {
            $this->redirect("index.php?page=404");
        }
        $modelAdmin = new Admin(Database::instance());
        $errors = $modelAdmin->getErrorLogs();
        $adminSuccess = $modelAdmin->getAdminSuccessLogs();
        $commentsLogs = $modelAdmin->getCommentsLogs();
        $loggedUsers = $modelAdmin->getAllLoggedUsers();
        $log = $modelAdmin->GetAllLogInfo();

        $this->data['erorrs'] = $errors;
        $this->data['adminSuccess'] = $adminSuccess;
        $this->data['commentsLogs'] = $commentsLogs;
        $this->data['loggedUsers'] = $loggedUsers;
        $this->data['log'] = $log;

        $this->loadView('logs', $this->data);
        $this->getLogInfo();
    }

    public function page404() {
        $this->loadView('page404', $this->data);
        $this->getLogInfo();
    }

    public function updateNewsPage() {
        if(isset($_GET['idNews'])){
            $idNews = $_GET['idNews'];
            $modelNews = new News(Database::instance());

            $singleData = $modelNews->GetPrintSingleData($idNews);

            $this->data['singleData'] = $singleData;

            $this->loadView("updateNews", $this->data);

        }
    }

    public function addNewsAjax() {

        if(isset($_POST['btnInsert']) && isset($_POST['tagField']) && isset($_POST['categories'])){

            $title = $_POST['title'];
            $content = $_POST['content'];
            $tags = $_POST['tagField'];
            $categories = $_POST['categories'];

            $fajl_naziv = $_FILES['news_photo']['name'];
            $fajl_tmpLokacija = $_FILES['news_photo']['tmp_name'];
            $fajl_tip = $_FILES['news_photo']['type'];
            $fajl_velicina = $_FILES['news_photo']['size'];

            $errors = [];

            $dozvoljeni_tipovi = ['image/jpg', 'image/jpeg', 'image/png'];

            if(!in_array($fajl_tip, $dozvoljeni_tipovi)){
                array_push($errors, "Pogresan tip fajla. - Profil slika");
                $this->redirect("index.php?page=addNews");
            }
            if($fajl_velicina > 3000000){
                array_push($errors, "Maksimalna velicina fajla je 3MB. - Profil slika");
                $this->redirect("index.php?page=addNews");

            }
            if(strlen($content) == 0){
                array_push($errors, "Mora se neki opis napisati");
                $this->redirect("index.php?page=addNews");
            }
            if(strlen($title) == 0){
                array_push($errors, "Mora se neki title napisati");
                $this->redirect("index.php?page=addNews");
            }



            if(empty(array_filter($tags))) {
                $errors[] = "Nema unetih tagova";
                $this->redirect("index.php?page=addNews");
            }

            list($sirina, $visina) = getimagesize($fajl_tmpLokacija);


            $postojecaSlika = null;
            switch($fajl_tip){
                case 'image/jpeg':
                    $postojecaSlika = imagecreatefromjpeg($fajl_tmpLokacija);
                    break;
                case 'image/png':
                    $postojecaSlika = imagecreatefrompng($fajl_tmpLokacija);
                    break;
            }

            $big_photoVisina = 750;
            $big_photoSirina = 550;

            $sidebard_photoVisina = 122;
            $sidebard_photoSirina = 106;

            $recent_photoVisina = 80;
            $recent_photoSirina = 80;

            $filter_photoVisina = 230;
            $filter_photoSirina = 185;

            $big_photoSlika = imagecreatetruecolor($big_photoVisina, $big_photoSirina);
            $sidebard_photoSlika = imagecreatetruecolor($sidebard_photoVisina, $sidebard_photoSirina);
            $recent_photoSlika = imagecreatetruecolor($recent_photoVisina, $recent_photoSirina);
            $filter_photoSlika = imagecreatetruecolor($filter_photoVisina, $filter_photoSirina);

            imagecopyresampled($big_photoSlika, $postojecaSlika, 0, 0, 0, 0, $big_photoVisina, $big_photoSirina, $sirina, $visina);
            imagecopyresampled($sidebard_photoSlika, $postojecaSlika, 0, 0, 0, 0, $sidebard_photoVisina, $sidebard_photoSirina, $sirina, $visina);
            imagecopyresampled($recent_photoSlika, $postojecaSlika, 0, 0, 0, 0, $recent_photoVisina, $recent_photoSirina, $sirina, $visina);
            imagecopyresampled($filter_photoSlika, $postojecaSlika, 0, 0, 0, 0, $filter_photoVisina, $filter_photoSirina, $sirina, $visina);

            $naziv = time().$fajl_naziv;
            $putanjabig_photoSlika = 'public/assets/images/big_photo'.$naziv;
            $putanjasidebard_photoSlika = 'public/assets/images/sidebar_photo'.$naziv;
            $putanjarecent_photoSlika = 'public/assets/images/recent_photo'.$naziv;
            $putanjafilter_photoSlika = 'public/assets/images/filter_photo'.$naziv;

            switch($fajl_tip){
                case 'image/jpeg':
                    imagejpeg($big_photoSlika, $putanjabig_photoSlika, 75);
                    imagejpeg($sidebard_photoSlika, $putanjasidebard_photoSlika, 75);
                    imagejpeg($recent_photoSlika, $putanjarecent_photoSlika, 75);
                    imagejpeg($filter_photoSlika, $putanjafilter_photoSlika, 75);
                    break;
                case 'image/png':
                    imagepng($big_photoSlika, $putanjabig_photoSlika);
                    imagepng($sidebard_photoSlika, $putanjasidebard_photoSlika);
                    imagepng($recent_photoSlika, $putanjarecent_photoSlika);
                    imagepng($filter_photoSlika, $putanjafilter_photoSlika);
                    break;
            }

            $putanjaOriginalnaSlika = 'public/assets/images/'.$naziv;

            if(move_uploaded_file($fajl_tmpLokacija, $putanjaOriginalnaSlika)){

                $idUser = $_SESSION['user']->id_user;
                $vremeUnosa = date("Y-m-d H-i-s", time());
                try {
                    $modelAdmin = new Admin(Database::instance());
                     $modelAdmin->insertNews($title, $content, $vremeUnosa, $idUser, $putanjabig_photoSlika, $putanjasidebard_photoSlika, $putanjarecent_photoSlika, $putanjafilter_photoSlika, $categories, $tags);
                    $this->adminLog("addNewsAjax()", "INSERT", 201);

                } catch (\PDOException $ex){
                    $this->errorLog("addNewsAjax()", $ex->getMessage());
                }
            }


        } else {
            $this->json(null, 403);
            $this->redirect("index.php?page=addNews");
        }

    }
    //brisanje news
    public function deleteNews() {
        if(isset($_POST['confirmDelete']) && isset($_POST['hiddenIdNews'])) {
            $idNews = $_POST['hiddenIdNews'];
            try{
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->deleteNews($idNews);

                $this->adminLog("deleteNews()", "Delete", 204);

            } catch (\PDOException $ex) {
                $this->redirect("index.php?page=404");
                http_response_code(500);
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->error_log("deleteNews()", $ex->getMessage(), date("Y-m-d H-i-s", time()));
            }


        } else {
            $this->json(null, 403);
            $this->redirect("index.php");
        }
    }

    //za ispis u update-u tagova
    public function getNewsTags() {
        if(isset($_GET['idNews'])) {
            try {
                $modelNews = new News(Database::instance());
                $tags = $modelNews->GetNewsTags($_GET['idNews']);

                $this->json($tags);
            } catch (\PDOException $ex) {
                $this->errorLog("getNewsTags()", $ex->getMessage());
            }
        } else {
            $this->json(null, 403);
        }
    }


    //birsem prilkom update news tag!!!!
    public function removeTagFromDb(){
        if(isset($_POST['oldIdTag'])){

            try {
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->deleteTagsNewsWhileUpdating($_POST['oldIdTag']);

                $this->adminLog("removeTagFromDb()", "DELETE", 204);
            } catch (\PDOException $ex) {
                $this->errorLog("removeTagFromDb()", $ex->getMessage());
            }

        } else {
            $this->json(null, 403);
        }
    }


    public function updateNewsInfo() {
        parse_str(file_get_contents('php://input'), $_PUT);

        if(isset($_PUT['updateInfo'])){
            $title = $_PUT['updateTitle'];
            $content = $_PUT['updateContent'];
            $tags = $_PUT['tagField'];
            $oldTags = $_PUT['oldTagField'];
            $hiddenOldTag = $_PUT['hiddenTag'];
            $categories = $_PUT['categories'];
            $idNews = $_PUT['idNews'];

            $errors = [];
            if(strlen($content) == 0){
                array_push($errors, "Mora se neki opis napisati");
                $this->redirect("index.php?page=addNews");
            }
            if(strlen($title) == 0){
                array_push($errors, "Mora se neki title napisati");
                $this->redirect("index.php?page=addNews");
            }
            try {
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->updateInfoNews($title, $content, $tags, $oldTags, $hiddenOldTag, $categories, $idNews);

                $this->adminLog("updateNewsInfo()", "UPDATE", 204);

            } catch (\PDOException $ex) {
                $this->errorLog("updateNewsInfo()", $ex->getMessage());
            }


        } else {
            $this->json(null, 403);
            $this->redirect("index.php");
        }
    }
    public function updateNewsPhoto()
    {

        if (isset($_POST['news_photo_btn'])) {
            $idNews = $_POST['idNews'];

            $fajl_naziv = $_FILES['news_photo_update']['name'];
            $fajl_tmpLokacija = $_FILES['news_photo_update']['tmp_name'];
            $fajl_tip = $_FILES['news_photo_update']['type'];
            $fajl_velicina = $_FILES['news_photo_update']['size'];

            $errors = [];

            $dozvoljeni_tipovi = ['image/jpg', 'image/jpeg', 'image/png'];

            if (!in_array($fajl_tip, $dozvoljeni_tipovi)) {
                array_push($errors, "Pogresan tip fajla. - Profil slika");
                $this->redirect("index.php?page=addNews");
            }
            if ($fajl_velicina > 3000000) {
                array_push($errors, "Maksimalna velicina fajla je 3MB. - Profil slika");
                $this->redirect("index.php?page=addNews");
            }

            list($sirina, $visina) = getimagesize($fajl_tmpLokacija);


            $postojecaSlika = null;
            switch ($fajl_tip) {
                case 'image/jpeg':
                    $postojecaSlika = imagecreatefromjpeg($fajl_tmpLokacija);
                    break;
                case 'image/png':
                    $postojecaSlika = imagecreatefrompng($fajl_tmpLokacija);
                    break;
            }

            $big_photoVisina = 750;
            $big_photoSirina = 550;

            $sidebard_photoVisina = 122;
            $sidebard_photoSirina = 106;

            $recent_photoVisina = 80;
            $recent_photoSirina = 80;

            $filter_photoVisina = 230;
            $filter_photoSirina = 185;

            $big_photoSlika = imagecreatetruecolor($big_photoVisina, $big_photoSirina);
            $sidebard_photoSlika = imagecreatetruecolor($sidebard_photoVisina, $sidebard_photoSirina);
            $recent_photoSlika = imagecreatetruecolor($recent_photoVisina, $recent_photoSirina);
            $filter_photoSlika = imagecreatetruecolor($filter_photoVisina, $filter_photoSirina);

            imagecopyresampled($big_photoSlika, $postojecaSlika, 0, 0, 0, 0, $big_photoVisina, $big_photoSirina, $sirina, $visina);
            imagecopyresampled($sidebard_photoSlika, $postojecaSlika, 0, 0, 0, 0, $sidebard_photoVisina, $sidebard_photoSirina, $sirina, $visina);
            imagecopyresampled($recent_photoSlika, $postojecaSlika, 0, 0, 0, 0, $recent_photoVisina, $recent_photoSirina, $sirina, $visina);
            imagecopyresampled($filter_photoSlika, $postojecaSlika, 0, 0, 0, 0, $filter_photoVisina, $filter_photoSirina, $sirina, $visina);

            $naziv = time() . $fajl_naziv;
            $putanjabig_photoSlika = 'public/assets/images/big_photo' . $naziv;
            $putanjasidebard_photoSlika = 'public/assets/images/sidebar_photo' . $naziv;
            $putanjarecent_photoSlika = 'public/assets/images/recent_photo' . $naziv;
            $putanjafilter_photoSlika = 'public/assets/images/filter_photo' . $naziv;

            switch ($fajl_tip) {
                case 'image/jpeg':
                    imagejpeg($big_photoSlika, $putanjabig_photoSlika, 75);
                    imagejpeg($sidebard_photoSlika, $putanjasidebard_photoSlika, 75);
                    imagejpeg($recent_photoSlika, $putanjarecent_photoSlika, 75);
                    imagejpeg($filter_photoSlika, $putanjafilter_photoSlika, 75);
                    break;
                case 'image/png':
                    imagepng($big_photoSlika, $putanjabig_photoSlika);
                    imagepng($sidebard_photoSlika, $putanjasidebard_photoSlika);
                    imagepng($recent_photoSlika, $putanjarecent_photoSlika);
                    imagepng($filter_photoSlika, $putanjafilter_photoSlika);
                    break;
            }

            $putanjaOriginalnaSlika = 'public/assets/images/' . $naziv;

            if (move_uploaded_file($fajl_tmpLokacija, $putanjaOriginalnaSlika)) {
                try {
                    $modelAdmin = new Admin(Database::instance());
                    $modelAdmin->updateNewsPhoto($idNews, $putanjabig_photoSlika, $putanjasidebard_photoSlika, $putanjarecent_photoSlika, $putanjafilter_photoSlika);

                    $this->adminLog("updateNewsPhoto()", "UPDATE", 204);
                } catch (\PDOException $ex) {
                    $this->errorLog("updateNewsPhoto()", $ex->getMessage());
                }
            }
        } else {
                $this->json(null, 403);
                $this->redirect("index.php");
            }
    }


    public function addUser() {
        if(isset($_POST['btnAddUser'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $activity = $_POST['activity'];
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
                $modelAdmin = new Admin(Database::instance());
                $password = md5($password);
                $modelAdmin->AddUser($email, $password, $token, $activity, $created_at, $role);

                $this->adminLog("addUser()", "INSERT", 201);
            } catch (\PDOException $ex) {
                $this->errorLog("addUser()", $ex->getMessage());
            }
        } else { //endif isset
            $this->json(null, 403);
        }
    }


    public function getAllUsers() {
        try {
            $modelAdmin = new Admin(Database::instance());
            $users = $modelAdmin->getAllUsers();

            $this->json($users);
        } catch (\PDOException $ex) {
            $this->redirect("index.php?page=404");
            http_response_code(500);
            $modelAdmin = new Admin(Database::instance());
            $modelAdmin->error_log("getAllUsers()", $ex->getMessage(), date("Y-m-d H-i-s", time()));
        }
    }

    public function deleteUser() {
        parse_str(file_get_contents('php://input'), $_DELETE);
//        var_dump($_DELETE);
        if($_DELETE['btnDeleteUser']) {
            $idUser = $_DELETE['idUser'];
            try {
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->deleteUser($idUser);

                $this->adminLog("deleteUser()", "DELETE", 204);
            } catch (\PDOException $ex) {
                $this->errorLog("deleteUsers()", $ex->getMessage());
            }
        } else { //endif isset
            $this->json(null, 403);
        }
    }


    public function getOneUser() {
        try {
            $idUser = $_GET['idUser'];
            $modelAdmin = new Admin(Database::instance());
            $user = $modelAdmin->getOneUser($idUser);

            $this->json($user);
        } catch (\PDOException $ex) {
            $this->errorLog("getOneUser()", $ex->getMessage());
        }
    }

    public function updateUser() {
        parse_str(file_get_contents('php://input'), $_PUT);
        if(isset($_PUT['btnEditUser'])) {

            $email = $_PUT['email'];
            $role = $_PUT['role'];
            $idUser = $_PUT['idUser'];
            $activity = $_PUT['activity'];
            $token = sha1(rand()) . time();

            $errors = []; //array za greske


            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Mail - Wrong Format";
            }

            if(count($errors) > 0) {
                $this->json($errors, 422);
                exit;
            }
            try {
                $modelAdmin = new Admin(Database::instance());
                $modelAdmin->updateUser($email, $token, $activity, $role, $idUser);
                $this->adminLog("updateUser()", "INSERT", 204);
            } catch (\PDOException $ex) {
                $this->errorLog("updateUser()", $ex->getMessage());
            }
        } else { //endif isset
            $this->json(null, 403);
        }
    }


    public function searchUser() {
        try {
            $email = "%".strtolower($_GET['valueSearch'])."%";

            $modelAdmin = new Admin(Database::instance());
            $user = $modelAdmin->getUserByEmail($email);

            $this->json($user);
        } catch (\PDOException $ex) {
            $this->errorLog("searchUser()", $ex->getMessage());
        }
    }

}