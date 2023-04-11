$(document).ready(function(){
    $(".js-toggle-drawer, .menu-cover").click(function(){
        $(".js-drawer").toggleClass("t-drawer--mobile-hidden");
        $(".menu-cover").toggleClass("sidebar-cover")
    });


    $("#menu-electronic-diary-trigger").click(function(){
        $("#menu-electronic-diary").toggleClass("active");
    });

    $("#menu-integrations-trigger").click(function(){
        $("#menu-integrations").toggleClass("active");
    });


});