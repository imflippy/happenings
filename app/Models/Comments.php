<?php


namespace app\Models;


use app\Config\Database;

class Comments
{
    private $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function GetAllComments($idNews) {
        $params = [$idNews];
        $query = "SELECT c.*, u.email, u.id_role, u.id_user FROM comments c JOIN users u ON c.id_user = u.id_user WHERE id_news = ? ORDER BY created_at_commnet DESC";

        return $this->database->executeAll($query, $params);
    }

    //pagination
    public function GetAllCommentsPagination($idNews, $pagination) {
        $limitPagination = $pagination * 5;

        $params = [$idNews];
        $query = "SELECT c.*, u.email, u.id_role, u.id_user FROM comments c JOIN users u ON c.id_user = u.id_user WHERE id_news = ? ORDER BY created_at_commnet DESC LIMIT $limitPagination, 5";

        return $this->database->executeAll($query, $params);
    }

    //insert komentara u bazu
    public function insertComment($comment, $createdAt, $idUser, $idNews) {
        $params = [$comment, $createdAt, $idUser, $idNews];
        $query = "INSERT INTO comments VALUES (NULL, ?, ?, ?,?)";

        $this->database->insert_update($query, $params);

    }


    //delete komentara ajaxom
    public function deleteComment($idComm) {
        $params = [$idComm];
        $query = "DELETE FROM comments WHERE id_comment = ?";

        $this->database->insert_update($query, $params);
    }

    // metoda kojom uzimam podatke o komentaru koji zelim da update
    public function getFormUpdateComm ($idComm) {
        $params = [$idComm];
        $query = "SELECT * FROM comments WHERE id_comment = ?";

        return $this->database->executeOneRow($query, $params);
    }

    //izvrsavanje update komentara
    public function updateComment($comment, $idComm) {
        $param = [$comment, $idComm];
        $query = "UPDATE comments SET commnet = ? WHERE id_comment = ?";

        $this->database->insert_update($query, $param);

    }

}