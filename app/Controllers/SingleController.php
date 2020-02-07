<?php
namespace app\Controllers;

use app\Config\Database;
use app\Models\Comments;
use app\Models\News;

class SingleController extends FrontendController {

    public function __construct()
    {
        parent::__construct();

        if(isset($_GET['idNews'])){
            $model = new News(Database::instance());
            $model->updateViewBy1($_GET['idNews']);
        }

    }


    //metoda za prikaz single news
    public function singlePage() {
        if(isset($_GET['idNews'])){
            $idNews = $_GET['idNews'];
            $modelNews = new News(Database::instance());
            $modelComments = new Comments(Database::instance());

            $singleData = $modelNews->GetPrintSingleData($idNews);
            $allComments = $modelComments->GetAllComments($idNews);
            if($singleData == null) {
                $this->redirect("index.php?page=404");
            }

            $this->data['singleData'] = $singleData;
            $this->data['allComments'] = $allComments;

            $this->loadView("single", $this->data);
            $this->getLogInfo();

        }
    }

    //dohvatanje svih komentara za news iz baze
    public function GetAllComments() {
        if(isset($_GET['idNews']) && isset($_GET['pagComm'])){
            $idNews = $_GET['idNews'];
            $pagination = $_GET['pagComm'];

            $modelComment = new Comments(Database::instance());

            $comments = $modelComment->GetAllCommentsPagination($idNews, $pagination);

            $this->json($comments);
        }else if(isset($_GET['idNews'])){
            $idNews = $_GET['idNews'];
            $pagination = 0;

            $modelComment = new Comments(Database::instance());

            $comments = $modelComment->GetAllCommentsPagination($idNews, $pagination);

            $this->json($comments);
        } else {
            $this->json("No action", 403);
        }
    }

    //metod za insert komentara u bazu za taj post
    public function insertComment() {

        if(isset($_POST['btnInsertComment'])){
            $comment = $_POST['comment'];
            $idUser = $_SESSION['user']->id_user;
            $idNews = $_POST['idNews'];
            $created_at = date("Y-m-d H-i-s", time());


            $regComment = "/[0-9A-Za-z.,\n \r?!]*/";

            $errors = [];
            if($comment == "") {
                $errors[] = "Cant be empty comment";
                exit;
            }
            else if(!preg_match($regComment, $comment)) {
                $errors[] = "Wrong format comment";
                exit;
            }
            try {
                $modelComment = new Comments(Database::instance());
                $modelComment->insertComment($comment, $created_at, $idUser, $idNews);

                $this->commentLog($comment, "INSERT", 201);

            } catch (\PDOException $ex){
                $this->errorLog("insertComment()", $ex->getMessage());

            }


        } else {
            $this->json(null, 403);
        }
    }

    //metoda za delete komentara
    public function deleteComment() {
        parse_str(file_get_contents('php://input'), $_DELETE);
        if(isset($_DELETE['idComm'])) {
            try {
                $modelComment = new Comments(Database::instance());
                $modelComment->deleteComment($_DELETE['idComm']);

                $this->commentLog("/", "DELETE", 204);
            } catch (\PDOException $ex) {
                $this->errorLog("deleteComment()", $ex->getMessage());

            }

        } else {
            $this->json(null, 403);
        }
    }

    //get update info and fill form comment
    public function getFormUpdateComm() {
        if(isset($_GET['idComm'])) {

            try {
                $modelComment = new Comments(Database::instance());
                $data = $modelComment->getFormUpdateComm($_GET['idComm']);

                $this->json($data);
            } catch (\PDOException $ex) {
                $this->errorLog("getFormUpdateComm()", $ex->getMessage());

            }

        } else {
            $this->json(null, 403);
        }
    }

    //metoda za update komentara
    public function updateComment() {
        parse_str(file_get_contents('php://input'), $_PUT);

        if (isset($_PUT['btnUpdateComment'])) {
            $comment = $_PUT['comment'];
            $idComm = $_PUT['idComm'];

            $regComment = "/[0-9A-Za-z.,\n \r?!]*/";

            $errors = [];
            if($comment == "") {
                $errors[] = "Cant be empty comment";
                exit;
            }
            else if(!preg_match($regComment, $comment)) {
                $errors[] = "Wrong format comment";
                exit;
            }
            try {
                $modelComment = new Comments(Database::instance());
                $modelComment->updateComment($comment, $idComm);

                $this->commentLog("$comment", "UPDATE", 204);


            } catch (\PDOException $ex) {
                $this->errorLog("updateComment()", $ex->getMessage());

            }
        } else {
            $this->json(null, 403);
        }
    }

}
