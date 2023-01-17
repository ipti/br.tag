 var div = $("<div></div>").addClass("sidebar-cover");
        $("body").append(div); 

$(document).ready(function(){
    $(".fullmenu-toggle-button").click(function(){
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
    $(".sidebar-cover").click(function(){
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
    $("#box-menu").click(function(){
        $("#menu").toggleClass("hidden-menu");
        div.toggleClass("sidebar-cover")
    });
});