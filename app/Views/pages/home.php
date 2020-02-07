
        <div class="container-fluid">
            <div class="container">
                <div class="primary margin-15">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="owl-carousel owl-theme js section_margin line_hoz animate-box" id="slideshow_face">
                                <?php
                                foreach ($data['slider'] as $slide):
                                ?>
                                <div class="item">
                                    <figure class="alith_post_thumb_big">
                                        <span class="post_meta_categories_label">
                                            <?php foreach ($slide->categories as $cat): ?>
                                                <a href="index.php?page=cat&idCat=<?= $cat->id_category;?>" class="white-color"><?= $cat->category;?></a>
                                             <?php endforeach; ?>
                                        </span>
                                        <a href="index.php?page=single&idNews=<?= $slide->id_news; ?>"><img src="<?= $slide->big_photo; ?>" alt="<?= $slide->title_news; ?>"/></a>
                                    </figure>
                                    <h3 class="alith_post_title animate-box" data-animate-effect="fadeInUp">
                                        <a href="index.php?page=single&idNews=<?= $slide->id_news; ?>"><?= $slide->title_news; ?></a>
                                    </h3>
                                    <div class="alith_post_content_big">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12">
                                                <div class="post_meta_center animate-box">
                                                    <p><strong><?= $slide->email; ?></strong></p>
                                                    <span class="post_meta_date"><?= date("d. M Y", strtotime($slide->created_at_news)); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-sm-12 animate-box">
                                                <p class="alith_post_except"><?= substr($slide->desc_news, 0, 120); ?>...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="post_list1 post_list_style_1">
                                <div class="alith_heading">
                                    <h2 class="alith_heading_patern_2">Recent Posts</h2>
                                </div>
                                <div id="allRecentPosts">

                                </div>



                                <div class="site-pagination fixedPag1 animate-box">
                                    <ul class="page-numbers">
<!--                                        <li><span class="page-numbers current" aria-current="page">1.</span></li>-->
<!--                                        <li><a href="#" class="page-numbers">2.</a></li>-->
<!--                                        <li><a href="#" class="page-numbers">3.</a></li>-->
<!--                                        <li><a href="#" class="page-numbers">4.</a></li>-->
                                        <?php
                                            $recentNews = $data['recent']; //vrednost koja dolazi iz loadView sa NewsController
                                            $numberOfRecentNews = count($recentNews);

                                            $numberOfPages = ceil($numberOfRecentNews / 5);
                                            for ($i = 1; $i <= $numberOfPages; $i++): ?>
                                                <li><a href="#" class="page-numbers recentPaginate" data-i="<?= $i - 1; ?>"><?= $i; ?>.</a></li>
                                            <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--Start Sidebar-->


