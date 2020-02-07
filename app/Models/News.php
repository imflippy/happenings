<?php
namespace app\Models;

use app\Config\Database;

class News {
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    //funkcija koja vraca categorije za ordedjenu news
    public function GetNewsCategories($idNews) {
        $params = [$idNews];
        $query = "SELECT c.category, c.id_category FROM categories c JOIN news_categories nc ON c.id_category = nc.id_category WHERE nc.id_news = ?";

        return $this->database->executeAll($query, $params);
    }

    public function GetNewsTags($idNews) {
        $params = [$idNews];
        $query = "SELECT t.id_tag, t.tag FROM tags t JOIN news_tags nt ON t.id_tag = nt.id_tag JOIN news n ON nt.id_news = n.id_news WHERE n.id_news = ?";

        return $this->database->executeAll($query, $params);
    }

    //funkcija koja vraca korisnke i news
    public function GetNewsUsers () {
        return $this->database->queryGet("SELECT n.id_news, n.title_news, n.created_at_news, n.recent_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user ORDER BY n.created_at_news DESC");
    }

    // funkciaj koja se ispisuje broj stranica paginacie za recent posts
    public function GetRecentPosts() {
        $newsUsers = $this->GetNewsUsers();

        foreach ($newsUsers as $nu) {
            $categories = $this->GetNewsCategories($nu->id_news);

            $nu->categories = $categories;
        }
        return $newsUsers;
    }

    //funkcija koja vraca korisnke i news za paginaciju
    public function GetNewsUsersPagination ($paginationPage) {
        $limitPagination = $paginationPage * 5;

        $query = ("SELECT n.id_news, n.title_news, n.created_at_news, n.recent_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user ORDER BY n.created_at_news DESC LIMIT $limitPagination, 5");

        return $this->database->queryGet($query);
    }

    // funkciaj koja se poziva ajaxom i ispisuju se recent posts za paginaciju
    public function GetRecentPostsPagination($paginationPage) {
        $newsUsers = $this->GetNewsUsersPagination($paginationPage);

        foreach ($newsUsers as $nu) {
            $categories = $this->GetNewsCategories($nu->id_news);

            $nu->categories = $categories;
        }
        return $newsUsers;
    }

    //za slajder na home page
    public function GetSliderNews() {
        return $this->database->queryGet("SELECT n.id_news, n.title_news, n.desc_news, n.created_at_news, n.big_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user ORDER BY n.created_at_news DESC LIMIT 3");
    }
    // funkciaj koja se poziva ajaxom i ispisuju se recent posts
    public function GetSliderPosts() {
        $news_users = $this->GetSliderNews();

        foreach ($news_users as $nu) {
            $categories = $this->GetNewsCategories($nu->id_news);

            $nu->categories = $categories;
        }
        return $news_users;
    }

    //funkcija koja dohvata kategorije i printuje ih u navigaciju
    public function GetCategories() {
        return $this->database->queryGet("SELECT * FROM categories");
    }

    //funkcja koja vraca tagove i printuje u sidebar i limitira na poslednjih 20
    public function GetTags() {
        return $this->database->queryGet("SELECT DISTINCT * FROM tags ORDER BY id_tag DESC LIMIT 25");
    }


    //funkciaj koja dohvata 5 najpopularnijih postova
    public function GetPopularNews() {
        return $this->database->queryGet("SELECT title_news, sidebard_photo, created_at_news, views, id_news FROM news ORDER BY views DESC LIMIT 5");
    }

    //funkcija koja se poziva prilikom search-a
    public function GetSearchNews($valueSearch) {
        $params = [$valueSearch];
        $query = "SELECT id_news, title_news, sidebard_photo, views, created_at_news FROM news WHERE LOWER(title_news) LIKE ? ORDER BY views DESC LIMIT 4";

        return $this->database->executeAll($query, $params);
    }

    //funciija koja updateuje i povecava views broj za news kada se pristupi njemu
    public function updateViewBy1($idNews) {
        $params = [$idNews];
        $query = "UPDATE news SET views = views + 1 WHERE id_news = ?";

        $this->database->insert_update($query, $params);
    }

    //pomocna f-ja za single novost
    public function GetNewsSingle($idNews) {
        $params = [$idNews];
        $query = "SELECT n.id_news, n.title_news, n.desc_news, n.created_at_news, n.big_photo, u.email, n.views FROM news n JOIN users u ON n.id_user = u.id_user WHERE n.id_news = ?";

        return $this->database->executeOneRow($query, $params);

    }

    //funkciaj za ispis single vesti
    public function GetPrintSingleData($idNews) {
        $singleNews = $this->GetNewsSingle($idNews);
            if(empty($singleNews)){
                return null;
            }

            $singleCats = $this->GetNewsCategories($idNews);
            $singleTags = $this->GetNewsTags($idNews);

            $singleNews->singleCategories = $singleCats;
            $singleNews->singleTags = $singleTags;

        return $singleNews;
    }

    //help funkcija za filter ajax categories
    public function GetPrepareFilterCategories($idCat, $paginationPage) {
        $limitPagination = $paginationPage * 5;
        $params = [$idCat];
        $query = "SELECT n.id_news, n.title_news, n.created_at_news, n.filter_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user JOIN news_categories nc ON n.id_news = nc.id_news JOIN categories c ON nc.id_category = c.id_category WHERE c.id_category = ? ORDER BY n.created_at_news DESC LIMIT $limitPagination, 5";

        return $this->database->executeAll($query, $params);
    }

    //funkcija za ajax filter categories
    public function GetFilterCategories($idCat, $paginationPage) {

        $prepareAjax = $this->GetPrepareFilterCategories($idCat, $paginationPage);

        foreach ($prepareAjax as $p) {
            $ispisSvihCatZaNews = $this->GetNewsCategories($p->id_news);

            $p->categories = $ispisSvihCatZaNews;
        }
        return $prepareAjax;

    }
    //////////// Koristim za Count na categry view
    /// Za ispis na category view podatke na pocetku strane
    public function getOneCat($idCat) {
        $params = [$idCat];
        $query = "SELECT category from categories WHERE id_category = ?";

        return $this->database->executeOneRow($query, $params);

    }

    public function GetPrepareFilterCategoriesAll($idCat) {
        $params = [$idCat];
        $query = "SELECT n.id_news, n.title_news, n.created_at_news, n.filter_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user JOIN news_categories nc ON n.id_news = nc.id_news JOIN categories c ON nc.id_category = c.id_category WHERE c.id_category = ? ORDER BY n.created_at_news DESC";

        return $this->database->executeAll($query, $params);
    }
    public function GetFilterCategoriesAll($idCat) {

        $prepareAjax = $this->GetPrepareFilterCategoriesAll($idCat);

        if(empty($prepareAjax)) {
            return null;
        }

        foreach ($prepareAjax as $p) {
            $ispisSvihCatZaNews = $this->GetNewsCategories($p->id_news);

            $p->categories = $ispisSvihCatZaNews;
        }
        return $prepareAjax;

    }
    ////////// End count category


    //help funkcija za filter ajax tags
    public function GetPrepareFilterTags($tag, $paginationPage) {
        $limitPagination = $paginationPage * 5;
        $params = [$tag];
        $query = "SELECT n.id_news, n.title_news, n.created_at_news, n.filter_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user JOIN news_tags nt ON n.id_news = nt.id_news JOIN tags t ON nt.id_tag = t.id_tag WHERE t.tag = ? ORDER BY n.created_at_news DESC LIMIT $limitPagination, 5";

        return $this->database->executeAll($query, $params);
    }

    //funkcija za ajax filter tags
    public function GetFilterTags($tag, $paginationPage) {

        $prepareAjax = $this->GetPrepareFilterTags($tag, $paginationPage);

        foreach ($prepareAjax as $p) {
            $ispisSvihCatZaNews = $this->GetNewsCategories($p->id_news);

            $p->categories = $ispisSvihCatZaNews;
        }
        return $prepareAjax;

    }


    //////////// Koristim za COUNT za tag strani
    // za ispis na tag view
    public function getOneTag($tag) {
        $props = [$tag];

        $query = "SELECT tag FROM tags WHERE tag = ?";

        return $this->database->executeOneRow($query, $props);
    }

    public function GetPrepareFilterTagsAll($tag) {
        $params = [$tag];
        $query = "SELECT n.id_news, n.title_news, n.created_at_news, n.filter_photo, u.email FROM news n JOIN users u ON n.id_user = u.id_user JOIN news_tags nt ON n.id_news = nt.id_news JOIN tags t ON nt.id_tag = t.id_tag WHERE t.tag = ? ORDER BY n.created_at_news DESC";

        return $this->database->executeAll($query, $params);
    }

    public function GetFilterTagsAll($tag) {

        $prepareAjax = $this->GetPrepareFilterTagsAll($tag);

        if(empty($prepareAjax)) {
            return null;
        }

        foreach ($prepareAjax as $p) {
            $ispisSvihCatZaNews = $this->GetNewsCategories($p->id_news);

            $p->categories = $ispisSvihCatZaNews;
        }
        return $prepareAjax;

    }

    ////////// END COUNT
}