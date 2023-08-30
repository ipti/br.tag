
$(".js-show-instructor-input").on("change", function (){
    
    if($(this).val() === "instructor"){
        $('.js-instructor-input').show()
    } else {
        $('.js-instructor-input').hide()
    }
})
$(".js-instructor-select").on("change", function (){
    var name = $(this).find(':selected').text()
     $('.js-chage-name').val(name);
});