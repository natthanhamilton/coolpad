$('#sidenav-open').click(function () {
    event.stopPropagation();
    document.getElementById("sidenav").style.left = "0";

});
$('.container, #sidenav-close').click(function () {
    document.getElementById("sidenav").style.left = "-270px";
});