
<?php
$recentNews = $data['allNewsCategory']; //vrednost koja dolazi iz loadView sa NewsController
$numberOfRecentNewsCategory = count($recentNews);
?>
<div class="container-fluid">
    <div class="container animate-box">
        <div class="row">
            <div class="archive-header">
                <div class="archive-title"><h2>  &amp; <?= $data['oneCat']->category; ?> &</h2></div>
                <!--                <div class="archive-description"><p>There is total --><?//= $numberOfRecentNewsTag; ?><!-- of news with "--><?//= $data['oneTag']->tag; ?><!--" tag</p></div>-->
                <div class="bread">
                    <ul class="breadcrumbs" id="breadcrumbs">
                        <li class="item-home"><a title="Home" href="index-2.html" class="bread-link bread-home">There is total <?= $numberOfRecentNewsCategory; ?> of news in this "<?= $data['oneCat']->category; ?>" category</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <div class="post_list post_list_style_1">
                        <div id="filterCat">

                        </div>
                        <div class="site-pagination animate-box">
                            <ul class="page-numbers">
                                <?php


                                $numberOfPages = ceil($numberOfRecentNewsCategory / 5);
                                for ($i = 1; $i <= $numberOfPages; $i++): ?>
                                    <li><a href="#" class="page-numbers recentPaginateCategory" data-i="<?= $i - 1; ?>"><?= $i; ?>.</a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>
                </div>