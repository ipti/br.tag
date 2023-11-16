
$(".js-show-instructor-input").on("change", function () {

    if ($(this).val() === "instructor") {
        $('.js-instructor-input').show()
    } else {
        $('.js-instructor-input').hide()
        $(".js-instructor-select").val("");
    }
})
$(".js-instructor-select").on("change", function () {
    if ($('.js-chage-name').val() === "") {
        const name = $(this).find(':selected').text()
        $('.js-chage-name').val(name);
    }
});
$(document).on("click", "#addSchool", function () {
    $("#addSchools").modal("show");
});

$(document).on("click", ".saveSchool", function () {
    const selecteds = $(".optionSchool").select2("data");
    $("#addSchool").val('Número de opções selecionadas: ' + selecteds.length);
});
