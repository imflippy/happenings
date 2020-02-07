
<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <article class="section_margin">
                        <div class="post-content">
                            <form novalidate="" class="comment-form" id="" method="post" action="index.php?page=addNewsAjax" enctype="multipart/form-data">
                                <p class="comment-notes"><span id="email-notes">Add News</span></p>
                                <p class="comment-form-comment"><label for="author">News Title (*)</label> <input type="text" id="addTitle" name="title" placeholder="Nes Title" value="" size="30"></p>
                                <p class="comment-form-comment"><label for="comment">News Content (*) <a href="#" id="newRow" class="content-helper">NewRow</a> <a href="#" id="newQuote" class="content-helper">NewQuote</a><a href="#" id="boldText" class="content-helper">BoldText</a></label><textarea aria-required="true" placeholder="News Content" rows="8" cols="45" name="content" id="idContent"></textarea></p>
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
                                   <span><input type="checkbox" name="categories[]" class="categories" value="<?= $cat->id_category; ?>"><?= $cat->category; ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <input type="file" name="news_photo">

                                <p class="form-submit comment-form-comment"><input type="submit" value="Add" class="submit " id="addNews" name="btnInsert" style="width: 100%"></p>
<!--                                    <input type="button" value="Clear Form" class="submit" id="clearForm" name="submit"> </p>-->

                            </form>

                        </div>
                    </article>
                </div>