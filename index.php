<?php

require_once "app/Config/setup.php";
require_once "app/Config/config.php";


use app\Controllers\LoginController as Login;
use app\Controllers\RegisterController as Register;
use app\Controllers\NewsController as News;
use app\Controllers\SingleController as Single;
use app\Controllers\CategoriesController as Category;
use app\Controllers\TagsController as Tag;
use app\Controllers\FrontendController as Frontend;
use app\Controllers\AdminNewsController as AdminNews;


$loginController = new Login();
$registerController = new Register();
$newsController = new News();
$singleController = new Single();
$categoryController = new Category();
$tagController = new Tag();
$frontendController = new Frontend();
$adminNewsController = new AdminNews();


if(isset($_GET['page'])){
    switch ($_GET['page']){
        case 'home':  //case koji prikazuje home view
            $newsController->GetSliderNews();
            break;
        case 'login': //case koji prikazuje login view
            $loginController->loginPage();
            break;
        case 'do-login': // odradjuej login usera
            $loginController->login();
            break;
        case 'logout': // odradjuje logout usera
            $loginController->logout();
            break;
        case 'register': // case za prikaz register view
            $registerController->registerPage();
            break;
        case 'do-register': // odradjuje register usera
            $registerController->register();
            break;
        case 'confirmRegister': // case vraca views gde korisnik dodje prilikom prihvatanja aktivacion linka
            $registerController->registerConfirmPage();
            break;
        case 'getRecentPostsPaginate': //
            $newsController->GetRecentNewsPaginate();
            break;
        case 'searchNews': // serach logika
            $frontendController->GetSearchNews();
            break;
        case 'single': //ucitava stranu za single vest i detalje o njoj
            $singleController->singlePage();
            break;
        case 'cat': // ucitva stranu za filter po cat
            $categoryController->categoriesPage();
            break;
        case 'filterCat': // ajax gadja ovu rutu i ispisuje filtrirane categorije
            $categoryController->categoriesAjax();
            break;
        case 'tag':
            $tagController->tagPage();
            break;
        case 'filterTag':
            $tagController->tagsAjax();
            break;
        case 'getAllComments': // prikaz svih komentara za odredjen news ajaxom
            $singleController->GetAllComments();
            break;
        case 'insertComment': // insert komentara preko ajaxa
            $singleController->insertComment();
            break;
        case 'deleteComm':  // brisem komentar
            $singleController->deleteComment();
            break;
        case 'getUpdateCommInfo': // dohvatam podakte o komentaru i upisujem u formu
            $singleController->getFormUpdateComm();
            break;
        case 'updateComm': // update komentara
            $singleController->updateComment();
            break;
        case 'addNews':
            $adminNewsController->addNewsPage();
            break;
        case 'addNewsAjax': // dodavanje novoe vesti preko admin panela za dodavanje bez ajax-a
            $adminNewsController->addNewsAjax();
            break;
        case 'deleteNews':  // brisanje vesti, samo admin moze da borise
            $adminNewsController->deleteNews();
            break;
        case 'updateNewsPage':
            $adminNewsController->updateNewsPage();
            break;
        case 'getTagsNews': // dohvatam tagove da bih ispisao tagove kod update u ap
            $adminNewsController->getNewsTags();
            break;
        case 'deleteTagFromDatabase': // brisem prilikom update new tag
            $adminNewsController->removeTagFromDb();
            break;
        case 'updateNewsInfo': // update za sve osim slike kod vesti
            $adminNewsController->updateNewsInfo();
            break;
        case 'updateNewsPhoto': // update za sliku vesti
            $adminNewsController->updateNewsPhoto();
            break;
        case 'users':
            $adminNewsController->users();
            break;
        case 'addUser':
            $adminNewsController->addUser();
            break;
        case 'getAllUsers':
            $adminNewsController->getAllUsers();
            break;
        case 'deleteUser':
            $adminNewsController->deleteUser();
            break;
        case 'getOneUser':
            $adminNewsController->getOneUser();
            break;
        case 'updateUser':
            $adminNewsController->updateUser();
            break;
        case 'searchUser':
            $adminNewsController->searchUser();
            break;
        case '404':
            $adminNewsController->page404();
            break;
        case 'logs':
            $adminNewsController->logPage();
            break;
        default:
            $newsController->GetSliderNews();
            break;
    }
} else {
    $newsController->GetSliderNews();
}

