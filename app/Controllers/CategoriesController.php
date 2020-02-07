<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\News;

class CategoriesController extends FrontendController {
    public function __construct()
    {
        parent::__construct();
    }

    //metoda koja vraca view filter category
    public function categoriesPage() {

        $model = new News(Database::instance());
        $podaci = $model->GetFilterCategoriesAll($_GET['idCat']);
        $catViewa = $model->getOneCat($_GET['idCat']);

        if($podaci == null) {
            $this->redirect("index.php?page=404");
        }

        $this->data['oneCat'] = $catViewa;
        $this->data['allNewsCategory'] = $podaci; // svi podaci koji su za odredjenu kategoriju - saljem zbog prebrojavanja i ispisivanja paginacije

        $this->loadView("category", $this->data);
        $this->getLogInfo();
    }

    public function categoriesAjax() {
        if(isset($_GET['idCategory']) && isset($_GET['pagePagCategory'])){
            $paginationPage = $_GET['pagePagCategory'];

            $model = new News(Database::instance());
            $podaci = $model->GetFilterCategories($_GET['idCategory'], $paginationPage);

            $this->json($podaci);
        } else if(isset($_GET['idCategory'])){
            $paginationPage = 0;

            $model = new News(Database::instance());
            $podaci = $model->GetFilterCategories($_GET['idCategory'], $paginationPage);

            $this->json($podaci);
        }
        else {
            $this->redirect("index.php?page=404");
        }
    }




}