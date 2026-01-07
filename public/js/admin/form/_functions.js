
$(".js-show-instructor-input").on("change", function (){

    if($(this).val() === "instructor"){
        $('.js-instructor-input').show()
    } else {
        $('.js-instructor-input').hide()
        $(".js-instructor-select").val("");
    }
})
$(".js-instructor-select").on("change", function (){
    if($('.js-chage-name').val() === ""){
        const name = $(this).find(':selected').text()
        $('.js-chage-name').val(name);
    }
});

$("select.js-role").on("change", function (){

    if( $(this).select2("val") == "guardian") {
       console.log($("select.js-schools"))
        $(".js-schools").hide()
    } else {
        $(".js-schools").show()
    }
})
