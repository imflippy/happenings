<?php

namespace app\Models;

use app\Config\Database;

class Admin {
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function insertNewsToCategory($idNews, $idCategoryNiz) {
        $queryParams = [];
        $values = [];

        foreach($idCategoryNiz as $idCat){
            $queryParams[] = "(NULL,?,?)";

            $values[] = $idNews;
            $values[] = $idCat;
        }

        $query = "INSERT INTO news_categories VALUES". implode(",", $queryParams);

        $this->database->insert_update($query, $values);
    }

    public function insertToTags($tags) {
        $tagNiz = [];

        foreach($tags as $tag){
            $tagNiz[] = $this->database->insert_update_id("INSERT INTO tags VALUES (NULL,?)", [$tag]);
        }

        return $tagNiz;
    }
    public function insertToNewsTags($idNews, $nizIdTags) {
        $queryParams = [];
        $values = [];

        foreach($nizIdTags as $tagId){
            $queryParams[] = "(NULL,?, ?)";

            $values[] = $idNews;
            $values[] = $tagId;
        }

        $query = "INSERT INTO news_tags VALUES". implode(",", $queryParams);

        $this->database->insert_update($query, $values);
    }

    public function insertNews($title, $desc, $createdAt, $idUser, $bigPhoto, $sidebarPhoto, $recentPhoto, $filterPhoto, $categories, $tags) {

        $params = [$title, $desc, $createdAt, $idUser, $bigPhoto, $sidebarPhoto, $recentPhoto, $filterPhoto];
        $query = "INSERT INTO news VALUES(NULL, ?, ?, ?, 0, ?, ?, ?, ?, ?)";

        $lastInsertId = $this->database->insert_update_id($query, $params);
        $this->insertNewsToCategory($lastInsertId, $categories);
        $nizIdTags = $this->insertToTags($tags);

        $this->insertToNewsTags($lastInsertId, $nizIdTags);
    }


    //metoda za brisanje news tags
    private function deleteNewsTags($idNews) {
        $params = [$idNews];

        $query = "DELETE FROM news_tags WHERE id_news = ?";

        $this->database->insert_update($query, $params);
    }

    //metoda za brisanje news categories
    private function deleteNewsCategories($idNews) {
        $params = [$idNews];

        $query = "DELETE FROM news_categories WHERE id_news = ?";

        $this->database->insert_update($query, $params);
    }

    // konacna metoda za brisanje vesti
    public function deleteNews($idNews) {

        $this->deleteNewsTags($idNews);
        $this->deleteNewsCategories($idNews);
        $params = [$idNews];
        $query = "DELETE FROM news WHERE id_news = ?";

        $this->database->insert_update($query, $params);

    }

    //ime funkcije sve govori
    public function deleteTagsNewsWhileUpdating($idTag) {
        $params = [$idTag];
        $query = "DELETE FROM news_tags WHERE id_tag = ?";

        //brise i iz tabele tags
        $query2 = "DELETE FROM tags WHERE id_tag = ?";

        $this->database->insert_update($query, $params);
        $this->database->insert_update($query2, $params);
    }
    //update news
    public function updateInfoNews($title, $content, $tags = null, $oldTags, $hiddenOldTag, $categories, $idNews) {
        $query = "UPDATE news SET title_news = ?, desc_news = ? WHERE id_news = ?";
        $params = [$title, $content, $idNews];

        $this->database->insert_update($query, $params);
        if($tags != null){
            $nizIdTags = $this->insertToTags($tags);
            $this->insertToNewsTags($idNews, $nizIdTags);
        }
        if($oldTags != null && $hiddenOldTag != null) {
            $this->updateOldTags($oldTags, $hiddenOldTag);
        }
        $this->deleteCategoriesNews($idNews);
        $this->insertNewsToCategory($idNews, $categories);

    }

    //brisem sve moguces kategorije za news pa zatim dodajem nove
    private function deleteCategoriesNews ($idNews) {
        $params = [$idNews];
        $query = "DELETE FROM news_categories WHERE id_news = ?";

        $this->database->insert_update($query, $params);
    }

    private function updateOldTags($oldTags, $hiddenOldTag) {
        for($i = 0; $i<count($oldTags); $i++) {
            $this->database->insert_update("UPDATE tags SET tag = ? WHERE id_tag = ?", [$oldTags[$i], $hiddenOldTag[$i]]);
        }
    }

    public function updateNewsPhoto($id_news, $putanjabig_photoSlika, $putanjasidebard_photoSlika, $putanjarecent_photoSlika, $putanjafilter_photoSlika) {
        $param = [$putanjabig_photoSlika, $putanjasidebard_photoSlika, $putanjarecent_photoSlika, $putanjafilter_photoSlika, $id_news];
        $query = "UPDATE news SET big_photo = ?, sidebard_photo = ?, recent_photo = ?, filter_photo = ? WHERE id_news = ?";

        $this->database->insert_update($query, $param);
    }

    public function getRoles() {
        return $this->database->queryGet("SELECT * FROM roles");
    }

    public function AddUser($email, $password, $token, $activity, $created_at, $role) {
        $param = [$email, $password, $token, $activity, $created_at, $role];
        $query = "INSERT INTO users VALUES (NULL, ?, ?, ?, ?, ?, ?)";

        $this->database->insert_update($query, $param);

    }

    public function getAllUsers() {
        return $this->database->queryGet("SELECT * FROM users u JOIN roles r ON u.id_role = r.id_role ORDER BY id_user DESC LIMIT 35");
    }

    public function deleteUser($idUser) {
        $parma = [$idUser];
        $query = "DELETE FROM users WHERE id_user = ?";

        $this->database->insert_update($query, $parma);
    }

    public function getOneUser($idUser) {
        $param = [$idUser];
        $query = "SELECT * FROM users u JOIN roles r ON u.id_role = r.id_role WHERE id_user = ?";

        return $this->database->executeOneRow($query, $param);
    }


    public function updateUser($email, $token, $activity, $role, $idUser) {
        $param = [$email, $token, $activity, $role, $idUser];
        $query = "UPDATE users SET email = ?, token = ?, active = ?, id_role = ? WHERE id_user = ?";

        $this->database->insert_update($query, $param);
    }

    public function getUserByEmail($email) {
        $param = [$email];
        $query = "SELECT * FROM users u JOIN roles r ON u.id_role = r.id_role WHERE LOWER(email) LIKE ? ORDER BY id_user DESC LIMIT 15";

        return $this->database->executeAll($query, $param);
    }


    public function error_log($action, $message, $time) {
        $param = [$action, $message, $time];
        $query = "INSERT INTO errors VALUES (NULL, ?, ?, ?)";

        $this->database->insert_update($query, $param);
    }

    public function admin_success($action, $message, $idUser, $time) {
        $param = [$action, $message, $idUser, $time];
        $query = "INSERT INTO admin_success VALUES (NULL, ?, ?, ?, ?)";

        $this->database->insert_update($query, $param);
    }

    public function commentLog($comment, $action, $idUser, $time) {
        $param = [$comment, $action, $idUser, $time];
        $query = "INSERT INTO comments_log VALUES (NULL, ?, ?, ?, ?)";

        $this->database->insert_update($query, $param);
    }

    public function getErrorLogs() {
        return $this->database->queryGet("SELECT * FROM errors ORDER BY error_time DESC");
    }
    public function getAdminSuccessLogs() {
        return $this->database->queryGet("SELECT ads.*, u.email FROM admin_success ads JOIN users u on ads.id_user = u.id_user ORDER BY admin_time DESC");
    }
    public function getCommentsLogs() {
        return $this->database->queryGet("SELECT cl.*, u.email FROM comments_log cl JOIN users u ON cl.id_user = u.id_user ORDER BY comment_time DESC");
    }

    public function loginAdd($ipAdress, $idUser){
        $param = [$ipAdress, $idUser];
        $query = "INSERT INTO loggin_count VALUES (NULL, ?, ?)";

        $this->database->insert_update($query, $param);
    }
    public function logginRemove($idUser){
        $param = [$idUser];
        $query = "DELETE FROM loggin_count WHERE id_user = ?";

        $this->database->insert_update($query, $param);
    }

    public function getAllLoggedUsers() {
        return $this->database->queryGet("SELECT * FROM users u JOIN loggin_count lc on u.id_user = lc.id_user JOIN roles r ON u.id_role = r.id_role");
    }

    public function getLogInfo($page, $ipAdress, $idUser, $time) {
        $param = [$page, $ipAdress, $idUser, $time];
        $query = "INSERT INTO logs VALUES (NULL, ?, ?, ?, ?)";

        $this->database->insert_update($query, $param);
    }

    public function GetAllLogInfo() {
        return $this->database->queryGet("SELECT * FROM logs l JOIN users u ON l.id_user = u.id_user ORDER BY time_log DESC");
    }

}