<?php
$recentNews = $data['allNewsTag']; //vrednost koja dolazi iz loadView sa NewsController
$numberOfRecentNewsTag = count($recentNews);
?>
<div class="container-fluid">
    <div class="container animate-box">
        <div class="row">
            <div class="archive-header">
                <div class="archive-title"><h2>  &amp; <?= $data['oneTag']->tag; ?> &</h2></div>
<!--                <div class="archive-description"><p>There is total --><?//= $numberOfRecentNewsTag; ?><!-- of news with "--><?//= $data['oneTag']->tag; ?><!--" tag</p></div>-->
                <div class="bread">
                    <ul class="breadcrumbs" id="breadcrumbs">
                        <li class="item-home"><a title="Home" href="index-2.html" class="bread-link bread-home">There is total <?= $numberOfRecentNewsTag; ?> of news with "<?= $data['oneTag']->tag; ?>" tag</a></li>
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
                        <div id="filterTag">

                        </div>
                        <div class="site-pagination animate-box">
                            <ul class="page-numbers">
                                <?php


                                $numberOfPages = ceil($numberOfRecentNewsTag / 5);
                                for ($i = 1; $i <= $numberOfPages; $i++): ?>
                                    <li><a href="#" class="page-numbers recentPaginateTag" data-i="<?= $i - 1; ?>"><?= $i; ?>.</a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>
                </div>
