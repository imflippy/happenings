<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\News;
//ova klasa sluzi za Home Views
class NewsController extends FrontendController {

    public function __construct()
    {
        parent::__construct();
    }

    //metod koji se poziva ajaxom na klik paginacije i odradjuje paginaciju
    public function GetRecentNewsPaginate() {
        if(isset($_GET['pagPage'])){
            try {
                $paginationPage = $_GET['pagPage'];
               $model = new News(Database::instance());
               $paginationData = $model->GetRecentPostsPagination($paginationPage);

               $this->json($paginationData);
            } catch (\PDOException $ex) {
                $this->errorLog("GetRecentNewsPaginate()25", $ex->getMessage());

            }
        } else {
            //u slucaju da nema parametar kroz url dobija vrdnost 0 i ispisuje prvih 5 recent news na home page
            try {
                $paginationPage = 0;
                $model = new News(Database::instance());
                $paginationData = $model->GetRecentPostsPagination($paginationPage);

                $this->json($paginationData);
            } catch (\PDOException $ex) {
                $this->errorLog("GetRecentNewsPaginate()37", $ex->getMessage());
            }

        }

    }

    //metod koji vraca 3 novosti za home page slajder
    public function GetSliderNews() {
        $model = new News(Database::instance());
        $slider = $model->GetSliderPosts();
        $recent = $model->GetRecentPosts();

        $this->data['slider'] = $slider;
        $this->data['recent'] = $recent;

        $this->loadView('home', $this->data);
        $this->getLogInfo();
    }

} // end class