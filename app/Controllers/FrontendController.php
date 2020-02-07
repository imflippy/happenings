<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\Admin;
use app\Models\News;

class FrontendController extends Controller {

    protected $data; //podaci koji su fixed na svim stranama sajta

    public function __construct()
    {
        $modelNews = new News(Database::instance());
        $navCategories = $modelNews->GetCategories(); //za ispis u navigaciji
        $popularNews = $modelNews->GetPopularNews();
        $tagsNews = $modelNews->GetTags();

        $this->data = [
            'categories' => $navCategories,
            'popularNews' => $popularNews,
            'tagsNews' => $tagsNews
        ];
    }

    //metod koji se poziva ajaxom i prikazuje news za search
    public function GetSearchNews() {

        if(isset($_GET['valueSearch'])) {
            try {
                $news ="%".strtolower($_GET['valueSearch'])."%";

                $model = new News(Database::instance());
                $model = $model->GetSearchNews($news);

                $this->json($model);
            } catch (\PDOException $ex) {
                $this->errorLog("GetSearchNews()", $ex->getMessage());
            }
        } else {
            $this->json("No search data", 403);
        }
    }

}