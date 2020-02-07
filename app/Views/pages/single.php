
<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <article class="section_margin">
                        <figure class="alith_news_img animate-box"><img src="<?= $data['singleData']->big_photo; ?>" alt="<?= $data['singleData']->title_news; ?>"/></figure>
                        <div class="post-content">
                            <div class="single-header">
                                <h3 class="alith_post_title"><?= $data['singleData']->title_news; ?></h3>
                                <div class="post_meta">
                                    <span class="meta_author_name"><a href="page-author.html" class="author"><?= $data['singleData']->email; ?></a></span>
                                    <span class="meta_categories">
                                        <?php
                                            foreach ($data['singleData']->singleCategories as $cat):
                                        ?>
                                        <a href="index.php?page=cat&idCat=<?= $cat->id_category; ?>"><?= $cat->category; ?></a>

                                        <?php endforeach; ?>
                                    </span>
                                    <span class="meta_date"><?= date("d M,Y", strtotime($data['singleData']->created_at_news)); ?></span>
                                    <span>Views: <?= $data['singleData']->views; ?></span>
                                </div>
                                <?php
                                if(isset($_SESSION['user'])): ?>
                                    <?php if($_SESSION['user']->id_role == 1): ?>
                                        <div class="reply comment-reply-link-div"> <a href="#" class="" id="deletePopUpShow" rel="nofollow">Delete News</a></div>
                                        <div class="reply comment-reply-link-div"> <a href="index.php?page=updateNewsPage&idNews=<?= $_GET['idNews']; ?>" class="" rel="nofollow">Update News</a></div>

                                        <div class="popUpDelete">
                                            <div><p>Click 'YES' to confirm or 'NO' to stop!</p></div>

                                            <div class="optionsDelete">

                                                <form action="index.php?page=deleteNews" method="POST">
                                                    <button type="submit" id="confirmDelete" class="deleteButtons" name="confirmDelete">YES</button>
                                                    <input type="hidden" id="hiddenIdNews" name="hiddenIdNews" value="<?= $_GET['idNews']; ?>">
                                                </form>

                                                <button type="button" class="deleteButtons" id="cancelDelete">No</button>
                                            </div>
                                        </div>



                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                            <div class="single-content animate-box">
<!--                                <p class="alith_post_except animate-box">Vivamus hac faucibus primis eleifend ligula curabitur phasellus augue, quisque rhoncus purus quam per felis rhoncus viverra bibendum, habitasse sem turpis fermentum</p>-->
                                <div class="dropcap column-1 animate-box">
                                   <?= $data['singleData']->desc_news; ?>
                                </div>
                                <div class="post-tags">
                                    <div class="post-tags-inner">
                                        <?php
                                        foreach ($data['singleData']->singleTags as $tag):
                                        ?>
                                        <a rel="tag" href="index.php?page=tag&tagname=<?= $tag->tag; ?>">#<?= $tag->tag; ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="post-share">
                                    <ul>
                                        <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li class="instagram"><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div> <!--single content-->
                            <div class="single-comment">
                                <section id="comments">
                                    <h4 class="single-comment-title">Comments</h4>
                                    <div class="comments-inner clr">
                                        <?php
                                        $numberOfComments = count($data['allComments']);
                                        $numberOfPages = ceil($numberOfComments / 5);
                                        ?>
                                        <div class="comments-title"> <p>There are <?= $numberOfComments; ?> comments for this article</p></div>
                                        <ol class="commentlist" id="allComments">


                                        </ol> <!--comment list-->
                                        <nav role="navigation" class="comment-navigation clr">
                                            <ul class="page-numbers">
                                            <?php
                                            for ($i = 1; $i <= $numberOfPages; $i++): ?>
                                                <li><a href="#" class="page-numbers PaginateComm" data-pagecomm="<?= $i - 1; ?>"><?= $i; ?>.</a></li>
                                            <?php endfor; ?>
                                            </ul>
                                        </nav> <!--comment nav-->
                                        <div class="comment-respond" id="respond">
                                            <h3 class="comment-reply-title" id="reply-title">Leave a Reply <small><a href="#respond" id="cancel-comment-reply-link" rel="nofollow"><i class="fa fa-times"></i></a></small></h3>
                                            <?php
                                            if(isset($_SESSION['user'])):
                                            ?>
                                            <form novalidate="" class="comment-form" id="commentform" method="post" action="#">
                                                <p class="comment-notes"><span id="email-notes">Your email address will be published.</span></p>
                                                <input type="hidden" id="hiddenComment">
                                                <p class="comment-form-comment"><label for="comment">Comment</label><textarea aria-required="true" placeholder="Your Comment" rows="8" cols="45" name="comment" id="comment"></textarea></p>

                                                <p class="form-submit"><input type="button" value="Post Comment" class="submit" id="insertComment" name="submit">
                                               <input type="button" value="Clear Form" class="submit" id="clearForm" name="submit"> </p>

                                            </form>
                                            <?php endif; ?>

                                            <?php
                                            if(!isset($_SESSION['user'])):
                                            ?>
                                            <p class="comment-notes"><span id="email-notes">Please <strong><a href="index.php?page=login">Login</a></strong> to comment!</span></p>

                                            <?php endif; ?>
                                        </div> <!--comment form-->

                                    </div>
                                </section>
                            </div>
                        </div>
                    </article>
                </div>