
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
$(document).on("click", "#addSchool", function () {
    $("#addSchools").modal("show");
});

$(document).on("click", "saveSchool", function () {
    // var multiselect = document.getElementByClassName('optionSchool');
    // var selectedOptions = [];
    // for (var i = 0; i < multiselect.options.length; i++) {
    //     if (multiselect.options[i].selected) {
    //         selectedOptions.push(multiselect.options[i].value);
    //     }
    // }
    // var numberOfSelectedOptions = selectedOptions.length;
    // alert('Número de opções selecionadas: ' + numberOfSelectedOptions);
});
