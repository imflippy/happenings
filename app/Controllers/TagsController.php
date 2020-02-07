<?php

namespace app\Controllers;

use app\Config\Database;
use app\Models\News;

class TagsController extends FrontendController {
    public function __construct()
    {
        parent::__construct();
    }

    //metoda koja vraca view filter TAG
    public function tagPage() {
        $model = new News(Database::instance());
        $podaci = $model->GetFilterTagsAll($_GET['tagname']);
        $tagViewa = $model->getOneTag($_GET['tagname']);

        if($podaci == null) {
            $this->redirect("index.php?page=404");
        }

        $this->data['oneTag'] = $tagViewa;
        $this->data['allNewsTag'] = $podaci;

        $this->loadView("tag", $this->data);
        $this->getLogInfo();
    }

    //metod koji ajax gadja i vraca podatke za ispis
    public function tagsAjax() {
        if(isset($_GET['tagname']) && isset($_GET['pagePagTag'])){
            $paginationPage = $_GET['pagePagTag'];

            $model = new News(Database::instance());
            $podaci = $model->GetFilterTags($_GET['tagname'], $paginationPage);

            $this->json($podaci);
        } else if(isset($_GET['tagname'])){
            $paginationPage = 0;

            $model = new News(Database::instance());
            $podaci = $model->GetFilterTags($_GET['tagname'], $paginationPage);

            $this->json($podaci);
        }
        else {
            $this->redirect("index.php");
        }
    }

}