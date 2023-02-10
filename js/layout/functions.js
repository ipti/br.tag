var div = $("<div></div>").addClass("sidebar-cover");
$("body").append(div);

$(document).ready(function () {
    if ($(".js-tag-table").length) {
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            $(".js-tag-table").dataTable({
                language: getLanguagePtbr(),
                responsive: true
            });
        });
    }
    $(".fullmenu-toggle-button").click(function () {
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
    $(".sidebar-cover").click(function () {
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
    $("#box-menu").click(function () {
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
});