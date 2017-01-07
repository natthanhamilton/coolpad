$(document).ready(function () {
    var allTags = document.getElementsByClassName("add_to_wishlist");
    for (var i = 0, len = allTags.length; i < len; i++) {
        var content = allTags[i];
        content.innerHTML = "";
    }

    $("img")
        .addClass("img-responsive");
});
