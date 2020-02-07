<body>
<div id="wrapper">
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="container">
                <div class="top_bar margin-15">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 time">
                            <i class="fa fa-clock-o"></i><span class="mr-1"> <?= date("l, d F Y", time()); ?></span>
                            <?php
                            if(!isset($_SESSION['user'])):
                            ?>
                            <span class="my-border-left p-2"><a href="index.php?page=login">Login</a></span>
                            <span class="my-border-left p-2"><a href="index.php?page=register">Register</a></span>
                            <?php endif; ?>
                            <?php
                            if(isset($_SESSION['user'])): ?>
                            <span class="my-border-left p-2"><a href="index.php?page=logout">Logout</a></span>
                                <?php if($_SESSION['user']->id_role == 1): ?>
                                <span class="my-border-left p-2"><a href="index.php?page=addNews">AddNews</a></span>
                                <span class="my-border-left p-2"><a href="index.php?page=users">Users</a></span>
                                <span class="my-border-left p-2"><a href="index.php?page=logs">Logs</a></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 col-sm-12 social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                            <div class="top-search">
                                <i class="fa fa-search"></i><span>SEARCH</span>
                            </div>
                            <div class="top-search-form">
                                <form action="#" class="search-form" method="get" role="search">
                                    <label>
                                        <span class="screen-reader-text">Search for:</span>
                                        <input type="search" id="search_news" name="s" value="" placeholder="Search …" class="search-field">
                                    </label>
                                    <input type="submit" value="Search" class="search-submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-12 header">
                        <h1 class="logo"><a href="index.php">HAPPENINGS</a></h1>
                        <p class="tagline">NEWSPAPER / MAGAZINE / PUBLISHER</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-nav section_margin">
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-12 main_nav_cover" id="nav">
                            <ul id="main-menu">
                                <li><a href="index.php">Home</a></li>
                                <?php
                                foreach ($data['categories'] as $cat):
                                ?>
                                <li><a href="index.php?page=cat&idCat=<?= $cat->id_category; ?>"><?= $cat->category; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <div class="row">
                    <div class="display-flex container" id="slider-small">


                    </div>
                </div>

