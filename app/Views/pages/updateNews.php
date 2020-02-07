
<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <article class="section_margin">
                        <div class="post-content">
                            <form novalidate="" class="comment-form" method="post" action="index.php?page=updateNewsInfo">
                                <input type="hidden" name="idNews" value="<?= $_GET['idNews'] ?>">
<!--                                <input type="hidden" name="tagField[]" value="0">-->
                                <p class="comment-notes"><span id="email-notes">Update News</span></p>
                                <p class="comment-form-comment"><label for="author">News Title (*)</label> <input type="text" id="updateTitle" name="updateTitle" placeholder="Nes Title" value="<?= $data['singleData']->title_news; ?>" size="30"></p>
                                <p class="comment-form-comment"><label for="comment">News Content (*) <a href="#" id="newRow" class="content-helper">NewRow</a> <a href="#" id="newQuote" class="content-helper">NewQuote</a><a href="#" id="boldText" class="content-helper">BoldText</a></label><textarea aria-required="true" placeholder="News Content" rows="8" cols="45" name="updateContent" id="updateContent"><?= $data['singleData']->desc_news; ?></textarea></p>
                                <label for="author">Tags (*)</label>
                                <div class="comment-form-author tagsDiv" id="tagsDiv">
                                    <!--                                    <span><input type="text" class="tagField" name="author" placeholder="Tag" value="" size="30"> <i class="fa fa-times removeTag" aria-hidden="true"></i></span>-->
                                </div>
                                <div class="reply comment-reply-link-div editButton"> <a href="#" class="" id="addTag" rel="nofollow">Add Tag Field</a></div>
                                <label for="author" class="categoryLabel">Choose Category (*)</label>
                                <div class="comment-form-author categoryListDiv" id="categoryListDiv">
                                    <?php
                                    foreach ($data['categories'] as $cat):
                                        ?>
                                    <?php
                                        if(in_array($cat,$data['singleData']->singleCategories)): ?>
                                        <span><input type="checkbox" name="categories[]" class="categories" value="<?= $cat->id_category; ?>" checked="checked"><?= $cat->category; ?></span>
                                       <?php else: ?>
                                        <span><input type="checkbox" name="categories[]" class="categories" value="<?= $cat->id_category; ?>"><?= $cat->category; ?></span>
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                </div>
                                <p class="form-submit comment-form-comment"><input type="submit" value="Update Info" class="submit " name="updateInfo" style="width: 100%"></p>
                            </form>
                            <form novalidate="" class="comment-form" id="" method="post" action="index.php?page=updateNewsPhoto" enctype="multipart/form-data">
                                <input type="hidden" name="idNews" value="<?= $_GET['idNews'] ?>">
                                <input type="file" name="news_photo_update">
                                <p class="form-submit comment-form-comment"><input type="submit" value="Update Photo" name="news_photo_btn" class="submit " name="" style="width: 100%"></p>
                            </form>

                        </div>
                    </article>
                </div>