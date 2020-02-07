<aside class="col-md-4 sidebar_right">
    <div class="sidebar-widget animate-box">
        <div class="widget-title-cover"><h4 class="widget-title"><span>Popular Articles</span></h4></div>
        <div class="latest_style_1">
            <?php
                $br = 1;
                foreach ($data['popularNews'] as $pn) :
            ?>
            <div class="latest_style_1_item">
                <span class="item-count vertical-align"><?= $br++; ?></span>
                <div class="alith_post_title_small">
                    <a href="index.php?page=single&idNews=<?= $pn->id_news; ?>"><strong class="change-font-size"><?= substr($pn->title_news, 0, 60); ?>...</strong></a>
                    <p class="meta"><span><?= date("M, d.Y", strtotime($pn->created_at_news)); ?></span> <span><?= $pn->views; ?> views</span></p>
                </div>
                <figure class="alith_news_img"><a href="index.php?page=single&idNews=<?= $pn->id_news; ?>"><img src="<?= $pn->sidebard_photo; ?>" alt="Popular Photo<?= $pn->id_news; ?>"/></a></figure>
            </div>
            <?php endforeach; ?>
        </div>
    </div> <!--.sidebar-widget-->

    <div class="sidebar-widget animate-box">
        <div class="widget-title-cover"><h4 class="widget-title"><span>Recent Tags</span></h4></div>
        <div class="alith_tags_all">
            <?php
                foreach ($data['tagsNews'] as $tag):
            ?>
                <a href="index.php?page=tag&tagname=<?= $tag->tag;?>" class="alith_tagg"><?= $tag->tag; ?></a>

            <?php endforeach; ?>
        </div>
    </div> <!--.sidebar-widget-->

</aside>


<!--End Sidebar-->
</div>
</div> <!--.primary-->

</div>
</div>
