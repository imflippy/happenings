$(document).ready(function () {
    $("#btnRegister").click(registration);

    $("#search_news").keyup(searchNews);

    // $('#addNews').click(addNews);



    //add new user
    $("#btnAddUser").click(btnAddUser);

    //prikaz delete popup
    $("#deletePopUpShow").click(function (event) {
        event.preventDefault();
        $(".popUpDelete").css({"visibility": "visible"});

    });

    //iskljucivanje popupa
    $("#cancelDelete").click(function (event) {
        event.preventDefault();

        $(".popUpDelete").css({"visibility": "hidden"});
    });

  // paginacija na home strani za recent news
    $(document).on('click', ".recentPaginate", function(event) {
        event.preventDefault();

        let recentPage = $(this).data('i');
        ajaxRecentPosts(recentPage);
    });

    //paginacija na category strani za kategorije
    $(document).on('click', ".recentPaginateCategory", function(event) {
        event.preventDefault();

        let recentPage = $(this).data('i');

        ajaxFilterCategory(recentPage);
    });

    //paginacija na tag strani za tag
    $(document).on('click', ".recentPaginateTag", function(event) {
        event.preventDefault();

        let recentPage = $(this).data('i');

        ajaxFilterTag(recentPage);
    });

    //brisanje komentara
    $(document).on('click', ".deleteComm", function(event) {
        event.preventDefault();

        let idComm = $(this).data('idcomm');
        // console.log(idComm);

        $.ajax({
            url: "index.php?page=deleteComm",
            dataType: "json",
            method: "DELETE",
            data: {
                idComm: idComm
            },
            success: function () {
                getAllComments();
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
    //update komentara
    $(document).on('click', ".editComm", function(event) {
        event.preventDefault();
        let idComm = $(this).data('idcomm');

        $.ajax({
            url: "index.php?page=getUpdateCommInfo&idComm=" + idComm,
            dataType: "json",
            method: "GET",
            success: function (data) {
                fillFormUpdateComm(data);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });



    //paginacija za komentare
    $(document).on('click', ".PaginateComm", function(event) {
        event.preventDefault();

        let idPage = $(this).data('pagecomm');
        let idNews = pageUrl.split("=");

        $.ajax({
            url: "index.php?page=getAllComments&idNews=" + idNews[2]+ "&pagComm=" + idPage,
            method: "GET",
            dataType: 'json',
            success: function (data) {
                // console.log(data, "vraceniComments");
                printAllComments(data);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

    });
    ajaxRecentPosts(0); //default klijent side render

    if(pageUrl.indexOf("page=single") != -1) {
        // prikaz svih komentara za news
        getAllComments();
    }

    // insert komentara preko ajaxa
    $("#insertComment").click(insertComment);
    //clear forme na klik dugmeta
    $("#clearForm").click(clearCommentForm);
    if(pageUrl.indexOf("page=cat") != -1){
        ajaxFilterCategory(0); // default klijent side render za categorije filtrirane
    }


    if(pageUrl.indexOf("page=tag") != -1) {
        ajaxFilterTag(0); // default klijent side render za tag filtriran
    }

    if(pageUrl.indexOf("page=users") != -1) {
        showAllUsers(); // default klijent side render za za korisnike u adminpanely
        $(document).on("click", ".removeUser", removeUser);
        $(document).on("click", "#btnClearFormUser", clearUsersForm);

        $("#serchUser").keyup(searchUser);

        $(document).on("click", ".editUser", function (event) {
            event.preventDefault();
            let idUser = $(this).data("edituser");

            $.ajax({
                url: "index.php?page=getOneUser",
                method: "GET",
                dataType: "json",
                data: {
                    idUser: idUser
                },
                success: function (data) {
                    fillUserForm(data);
                    // console.log(data);
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        });
    }

    //addNews.php -- add & remove tag field
    var idTag = 1;
    $("#addTag").click(function (event) {
        event.preventDefault();
        idTag++;
        $("#tagsDiv").append("<span id='tagField"+idTag+"'><input type='text' class='tagField' name='tagField[]' placeholder='Tag' value='' size='30'> <i class='fa fa-times removeTag' data-idremove='"+idTag+"' aria-hidden='true'></i></span>");
    });


    if(pageUrl.indexOf("page=updateNewsPage") != -1) {
        printTagsFroUpdate();


        //removing tags from data base while updating news
        // $(".removeFromDb").click(removeFromDb);
        $(document).on("click", ".removeFromDb", removeFromDb)
    }


    $(document).on('click', '.removeTag', function () {
        let removeId = $(this).data('idremove');
        $("#tagField" + removeId + "").remove();
    });

    //dodavanje novog reda
    $("#newRow").click(function (event) {
        event.preventDefault();
            var noviRed = "<br><br><br>    ";
        $("#idContent").val($("#idContent").val() + noviRed);
        $("#idContent").focus();
    });

    //dodavanje quotes
    $("#newQuote").click(function (event) {
        event.preventDefault();
        var quote = "<blockquote>   </blockquote>";
        $("#idContent").val($("#idContent").val() + quote);
        $("#idContent").focus();
    });

    //bold text
    $("#boldText").click(function (event) {
        event.preventDefault();
        var strong = "<strong>   </strong>";
        $("#idContent").val($("#idContent").val() + strong);
        $("#idContent").focus();
    });
});

    //constanta koja sadrzi url adresu
    const pageUrl = window.location.href;

//funkcija za registraciju pomocu ajaxa
function registration() {
    let email = $("#email").val();
    let password = $("#password").val();
    let confirm_password = $("#confirm_password").val();

    let regexMail = /^[A-Za-z\d\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\;\"\(\)\,\:\;\<\>\@\[\\\]\.]{5,}[^\.]*\@(([a-z\d\-]+)\.[\w\d]+)+$/;
    let regexPassword =/^(?=.*\d).{6,}$/;

    let errors = [];

    if(!regexMail.test(email)) {
        errors.push("Mail je los");
        toastr.error("Email - Wrong Format!");
    }
    if(!regexPassword.test(password)) {
        errors.push("Password - Error");
        toastr.error("Password - At least 6 characters including number!");
    }
    if(password !== confirm_password) {
        errors.push("Confirm_Password - Error");
        toastr.error("Passwords must match!");
    }

    if (!errors.length) {
        $.ajax({
           url: "index.php?page=do-register",
            method: "POST",
            data: {
                email: email,
                password: password,
                btnRegister: true
            },
            success: function () {
                toastr.success("Please check Email and confirm registrtion");
            },
            error: function (xhr, status, error) {
               console.log(error);
                var responseCode = xhr.status;

                switch (responseCode) {
                    case 500:
                        toastr.error("Email already exists!");
                        break;
                    case 403:
                        toastr.error("Access Denied!");
                        break;
                    default:
                        toastr.error('Something went wrong. We will fix ASAP');
                        break;
                }
            }
        });
    }

} //end function Registration

//Function for login
function login() {
 // To Do
}


function printTagsFroUpdate() {
    let idNews = pageUrl.split("=");

    $.ajax({
       url: "index.php?page=getTagsNews",
        method: "GET",
        data: {
           idNews: idNews[2]
        },
        success: function (data) {
            console.log(data);
            let html = "";
            idTag = 99999;
            data.forEach(d => {
               idTag++;
               html += `<input type="hidden" name="hiddenTag[]" value="${d.id_tag}"/>
               <span id='tagField${idTag}'><input type='text' class='tagField' name='oldTagField[]' placeholder='Tag' value='${d.tag}' size='30'> <i class='fa fa-times removeTag removeFromDb' data-idfromdb="${d.id_tag}" data-idremove='${idTag}' aria-hidden='true'></i></span>
               `
            });
            $("#tagsDiv").append(html);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function removeFromDb() {
    let oldIdTag = $(this).data('idfromdb');
    console.log(oldIdTag);
    $.ajax({
        url: "index.php?page=deleteTagFromDatabase",
        method: "POST",
        data: {
            oldIdTag: oldIdTag,
        },
        success: function () {
            console.log("Delete Tag");
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}



// function for RecentPosts po default

function ajaxRecentPosts(recentPage) {

    $.ajax({
        url: "index.php?page=getRecentPostsPaginate&pagPage=" + recentPage,
        dataType: "json",
        method: "GET",
        success: function (data) {
            printRecentPosts(data);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}
// function for printing all data in html
function printRecentPosts(data) {
    let html = '';
    data.forEach(d =>{
        html += printRecentPost(d);
    });

    $("#allRecentPosts").html(html);
}
//function for printing one recent post
function printRecentPost(d) {
    return `
        <article class="row section_margin">
            <div class="col-md-3">
                <figure class="alith_news_img"><a href="index.php?page=single&idNews=${d.id_news}"><img src="${d.recent_photo}" alt="recentPhoto${d.id_news}"/></a></figure>
            </div>
            <div class="col-md-9">
                <h3 class="alith_post_title"><a href="index.php?page=single&idNews=${d.id_news}">${d.title_news}</a></h3>
                <div class="post_meta">
                    <span class="meta_author_name">${d.email}</span>
                    <span class="meta_categories">${printCategories(d.categories)}</span>
                    <span class="meta_date">${formatDate(d.created_at_news)}</span>
                </div>
            </div>
        </article>
    `
}
//function for categories used in recet posts
function printCategories(d) {
    let category = '';
    d.forEach(cat => {
        category += `<a href="index.php?page=cat&idCat=${cat.id_category}">${cat.category}</a>    `;
    });
    return category;
}
//function for formating date
function formatDate(data) {
    let timestampData = new Date(data);
    var monthNames = [
        "JAN", "FEB", "MAR",
        "APR", "MAY", "JUN", "JUL",
        "AUG", "SEP", "OCT",
        "NOV", "DEC"
    ];

    var day = timestampData.getDate();
    var monthIndex = timestampData.getMonth();
    var year = timestampData.getFullYear();

    return monthNames[monthIndex] + ', ' + day + '. ' + year;
}
//format datuma sa vremenom
function formatDateTime(data) {
    let timestampData = new Date(data);
    var monthNames = [
        "JAN", "FEB", "MAR",
        "APR", "MAY", "JUN", "JUL",
        "AUG", "SEP", "OCT",
        "NOV", "DEC"
    ];

    var minutes = timestampData.getMinutes();
    var hours = timestampData.getHours();
    var day = timestampData.getDate();
    var monthIndex = timestampData.getMonth();
    var year = timestampData.getFullYear();


    return monthNames[monthIndex] + ', ' + day + '. ' + year + " " + hours + ": " + minutes;
}


//funkcija za keyup search koji se dogaja odmah ispod navigacije tj kategorija
function searchNews() {
    let valueSearch = $(this).val();

    $.ajax({
       url: "index.php?page=searchNews&valueSearch=" + valueSearch,
        method: "GET",
        dataType: "json",
        success: function (data) {
            printSearchNews(data);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function printSearchNews(data) {
    let html = '';
    data.forEach(d => {
       html += `
            <div class="single-item p-4">
                <figure class="width-122px">
                    <a href="index.php?page=single&idNews=${d.id_news}"><img src="${d.sidebard_photo}" alt="${d.title_news}"/></a>
                </figure>
                <div class="">
                    <a href="single.html"><strong>${d.title_news}</strong></a>
                    <p class="datum-position"><span>${formatDate(d.created_at_news)}</span> <span>Views: ${d.views}</span></p>
                </div>
            </div>
       `;
    });
    $("#slider-small").html(html);
}
if(pageUrl.indexOf("page=cat") != -1){
    //funkcija koja filtrira po kategoriji
    function ajaxFilterCategory(recentPage) {
        var idCat = pageUrl.split("=");
        $.ajax({
            url: "index.php?page=filterCat&idCategory=" + idCat[2] + "&pagePagCategory=" + recentPage,
            method: "GET",
            dataType: "json",
            success: function (data) {
                console.log(data);
                printFilterCategory(data);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function printFilterCategory(d) {
        let html = "";

        d.forEach(news => {
            html += `
            <article class="row section_margin">
                <div class="col-md-4">
                    <figure class="alith_news_img"><a href="index.php?page=single&idNews=${news.id_news}"><img src="${news.filter_photo}" alt="slika${news.id_news}"/></a></figure>
                </div>
                <div class="col-md-8">
                    <h3 class="alith_post_title"><a href="index.php?page=single&idNews=${news.id_news}">${news.title_news}</a></h3>
                    <div class="post_meta">
                        <span class="meta_author_name"><a href="page-author.html" class="author">${news.email}</a></span>
                        <span class="meta_categories">${printCategories(news.categories)}</span>
                        <span class="meta_date">${formatDate(news.created_at_news)}</span>
                    </div>
                </div>
            </article>
        `;
        });

        $("#filterCat").html(html);

    }

}


if(pageUrl.indexOf("page=tag") != -1){
    //funkcija koja filtrira po tagovima i ispisuje default (po ucitavanju)
    function ajaxFilterTag(recentPage) {
        var tag = pageUrl.split("=");
        // console.log(idTag[2]);
        $.ajax({
            url: "index.php?page=filterTag&tagname=" + tag[2] + "&pagePagTag=" + recentPage,
            method: "GET",
            dataType: "json",
            success: function (data) {
                // console.log(data);
                printFilterTag(data);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function printFilterTag(d) {
        let html = "";

        d.forEach(news => {
            html += `
            <article class="row section_margin">
                <div class="col-md-4">
                    <figure class="alith_news_img"><a href="index.php?page=single&idNews=${news.id_news}"><img src="${news.filter_photo}" alt="slika${news.id_news}"/></a></figure>
                </div>
                <div class="col-md-8">
                    <h3 class="alith_post_title"><a href="index.php?page=single&idNews=${news.id_news}">${news.title_news}</a></h3>
                    <div class="post_meta">
                        <span class="meta_author_name"><a href="page-author.html" class="author">${news.email}</a></span>
                        <span class="meta_categories">${printCategories(news.categories)}</span>
                        <span class="meta_date">${formatDate(news.created_at_news)}</span>
                    </div>
                </div>
            </article>
        `;
        });

        $("#filterTag").html(html);

    }

}


function getAllComments() {
    let idNews = pageUrl.split("=");
    $.ajax({
        url: "index.php?page=getAllComments&idNews=" + idNews[2],
        method: "GET",
        dataType: "json",
        success: function (data) {
            printAllComments(data);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }

    });
}

function printAllComments(data) {
    let html = "";

    data.forEach(d =>{
        html += `
        <li id="li-comment-4">
            <article class="comment even thread-even depth-1 clr" id="comment-4">
                <div class="comment-details clr ">
                    <header class="comment-meta"> <strong class="fn"> ${d.email} </strong> - <span class="comment-date">${formatDateTime(d.created_at_commnet)}</span></header>
                    <div class="comment-content entry clr">
                        <p>${d.commnet}</p>
                    </div>
                    ${editButton(d)}
                    ${deleteButton(d)}
                </div>
            </article>
        </li>
        `;
    });

    $("#allComments").html(html);
}

function editButton(d) {
    if(idRole == 1 || d.id_user === idUser) {
        return `<div class="reply comment-reply-link-div editButton"> <a href="#" class="editComm" data-idComm="${d.id_comment}" rel="nofollow">Edit</a></div>`
    }
    return "";
}

function deleteButton(d) {
    if(idRole == 1 || d.id_user === idUser) {
        return `<div class="reply comment-reply-link-div"> <a href="#" class="deleteComm" data-idcomm="${d.id_comment}" rel="nofollow">Delete</a></div>`
    }
    return "";
}
function insertComment() {

    let comment = $("#comment").val();
    let idHidden = $("#hiddenComment").val();
    let regComment = /[0-9A-Za-z.,\n \r?!]*/;

    let errors = [];

    if(comment == "") {
        errors.push("Cant be empty");
        toastr.error("Comment cant be empty");
    }
    else if(!regComment.test(comment)) {
        errors.push("Not good format comment");
        toastr.error("Comment in not in good format");
    }

    if(!errors.length){
        let idNews = pageUrl.split("=");


        if(idHidden == ""){
            $.ajax({
                url: "index.php?page=insertComment",
                method: "POST",
                data: {
                    comment: comment,
                    idNews: idNews[2],
                    btnInsertComment: true
                },
                success: function () {
                    getAllComments();
                    clearCommentForm();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        } else {
            $.ajax({
                url: "index.php?page=updateComm",
                method: "PUT",
                data: {
                    comment: comment,
                    idComm: idHidden,
                    btnUpdateComment: true
                },
                success: function () {
                    getAllComments();
                    clearCommentForm();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        }


    }

}

function fillFormUpdateComm(data) {
    $("#hiddenComment").val(data.id_comment);
    $("#comment").val(data.commnet);
    $("#insertComment").val("Update Comment");
}

function clearCommentForm() {
    $("#hiddenComment").val("");
    $("#comment").val("");
    $("#insertComment").val("Post Comment");
}

//funkciaj za sladje ajaxa i dodavanje u bazu novih vesti
function addNews() {
    let title = $("#addTitle").val();
    let content = $("#idContent").val();
    let tags = [];
    let categories = [];

    $("#tagsDiv").find("[name='tagField']").each(
        function () {
            tags.push($(this).val());
        }
    );

    $("#categoryListDiv").find("[name='categories']:checked").each(
        function () {
            categories.push($(this).val());
        }
    );

    $.ajax({
       url: "index.php?page=addNewsAjax",
        method: "POST",
        data: {
            title: title,
            content: content,
            tags: tags,
            categories: categories,
            btnInsert: true
        },
        success: function () {
            console.log("Uspesan unos");
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });

}


function btnAddUser() {
    let email = $("#emailAdd").val();
    let password = $("#passwordAdd").val();
    let confirm_password = $("#confirm_passwordAdd").val();
    let role = $("input[name='role']:checked"). val();
    let activity = $("input[name='activity']:checked"). val();
    let hiddenUser = $("#hiddenUser").val();

    let regexMail = /^[A-Za-z\d\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\;\"\(\)\,\:\;\<\>\@\[\\\]\.]{5,}[^\.]*\@(([a-z\d\-]+)\.[\w\d]+)+$/;
    let regexPassword =/^(?=.*\d).{6,}$/;

    let errors = [];

    if(!regexMail.test(email)) {
        errors.push("Mail je los");
        toastr.error("Email - Wrong Format!");
    }
    if(role === undefined) {
        errors.push("Confirm_Password - Error");
        toastr.error("Please select role!");
    }
    if(activity === undefined) {
        errors.push("Confirm_Password - Error");
        toastr.error("Please select activity!");
    }

    if(hiddenUser == ""){
        if(!regexPassword.test(password)) {
            errors.push("Password - Error");
            toastr.error("Password - At least 6 characters including number!");
        }
        if(password !== confirm_password) {
            errors.push("Confirm_Password - Error");
            toastr.error("Passwords must match!");
        }

        if (!errors.length) {
            $.ajax({
                url: "index.php?page=addUser",
                method: "POST",
                data: {
                    email: email,
                    password: password,
                    role: role,
                    activity: activity,
                    btnAddUser: true
                },
                success: function () {
                    toastr.success("User has been added");
                    showAllUsers();
                    clearUsersForm();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    var responseCode = xhr.status;

                    switch (responseCode) {
                        case 500:
                            toastr.error("Email already exists!");
                            break;
                        case 403:
                            toastr.error("Access Denied!");
                            break;
                        default:
                            toastr.error('Something went wrong. We will fix ASAP');
                            break;
                    }
                }
            });
        }
    } else {
        if (!errors.length) {
            let idUser = $("#hiddenUser").val();
            $.ajax({
                url: "index.php?page=updateUser",
                method: "POST",
                data: {
                    email: email,
                    role: role,
                    activity: activity,
                    idUser: idUser,
                    btnEditUser: true
                },
                success: function () {
                    toastr.success("User has been added");
                    showAllUsers();
                    clearUsersForm();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    var responseCode = xhr.status;

                    switch (responseCode) {
                        case 500:
                            toastr.error("Email already exists!");
                            break;
                        case 403:
                            toastr.error("Access Denied!");
                            break;
                        default:
                            toastr.error('Something went wrong. We will fix ASAP');
                            break;
                    }
                }
            });
        }
    }


}

function clearUsersForm() {
    $("#emailAdd").val("");
    $("#passwordAdd").val("");
    $("#confirm_passwordAdd").val("");
    $("input[name='role']:checked").prop("checked", false);
    $("input[name='activity']:checked").prop("checked", false);
    $("#hiddenUser").val("");
    $("#btnAddUser").val("Add User");
    $(".passwords").css({"display": "block"});
}
function showAllUsers() {
    $.ajax({
        url: "index.php?page=getAllUsers",
        method: "GET",
        dataType: "json",
        success: function (data) {
            printUsers(data);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function printUsers(data) {
    let html = `<tr>
                    <td>Id User</td>
                    <td>Email</td>
                    <td>Activity</td>
                    <td>Role</td>
                    <td>Settings</td>
                </tr>`
    data.forEach(d => {
        html += `
        <tr>
            <td><a href="#" class="editUser" data-edituser="${d.id_user}">${d.id_user}</a></td>
            <td>${d.email}</td>
            <td>${d.active}</td>
            <td>${d.role}</td>
            <td><i class='fa fa-times removeUser' data-idremoveuser='${d.id_user}' aria-hidden='true'></i></td>
        </tr>
        `
    });
    $("#allUsers").html(html);
}
function removeUser() {
    let idUser = $(this).data('idremoveuser');
    $.ajax({
        url: "index.php?page=deleteUser",
        method: "DELETE",
        data: {
            idUser: idUser,
            btnDeleteUser: true
        },
        success: function () {
            // console.log("Obisan user");
            showAllUsers();
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    })

}

function fillUserForm(data) {
    $("#emailAdd").val(data.email);
    $(".passwords").css({"display": "none"});
    $("input[name='role'][value="+data.id_role +"]").prop('checked', true);
    $("input[name='activity'][value="+data.active +"]").prop('checked', true);
    $("#hiddenUser").val(data.id_user);
    $("#btnAddUser").val("Edit User");
}

function searchUser() {
    let valueSearch = $(this).val();

    $.ajax({
        url: "index.php?page=searchUser",
        method: "GET",
        dataType: "json",
        data: {
            valueSearch: valueSearch
        },
        success: function (data) {
            printUsers(data);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}
