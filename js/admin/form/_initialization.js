$('#Users_password').attr('value', '');


//update user
if($(".js-show-instructor-input").val() === "instructor"){
    $('.js-instructor-input').show();
}
$(document).ready(function() {
    const selecteds = $(".optionSchool").select2("data");
    if(selecteds.length <= 0){
        $("#addSchool").val('Selecione as escolas');
    } else {
        $("#addSchool").val('Número de opções selecionadas: ' + selecteds.length);
    }
});
