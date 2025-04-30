$(".js-cpf-mask").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(".js-cep-mask").mask("00000-000", {
    placeholder: "_____-___",
});
$(".js-tel-mask").mask("(00) 00000-0000", {
    placeholder: "(__) _____-____"
});

$(".js-filiation-select").on("change", () => {
    if ($(".js-filiation-select").select2("val") === "1") {
        $(".js-hide-filiation").css("display", "flex");
    } else {
        $(".js-hide-filiation").hide()
    }
});

$(".js-uf").on("change", () => {

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/getCities",
        cache: false,
        data: {
            state: $('select.js-uf').val(),
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);

            $("select.js-cities").html(data)
            $("select.js-cities").select2()
            $("select.js-cities").removeAttr("disabled");
        }
    })
})

$(".js-stage").on("change", () => {

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/getSchools",
        cache: false,
        data: {
            stage: $('select.js-stage').val(),
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);
            let schools = $("select.js-school-1, select.js-school-2, select.js-school-3");

            schools.html(data)
            schools.select2()
            schools.removeAttr("disabled");
        }
    })
})

$("select.js-school-1, select.js-school-2, select.js-school-3").on("change", function () {
    const currentVal = $(this).val();
    const currentSelect = this;

    let duplicateFound = false;

    $("select.js-school-1, select.js-school-2, select.js-school-3").each(function () {
        if (this !== currentSelect && $(this).val() === currentVal && currentVal !== "") {
            duplicateFound = true;
        }
    });

    if (duplicateFound) {
        $(".js-alert").text("Você não pode selecionar a mesma opção de matíricula mais de uma vez");
        $(".js-alert").show();
        $(this).select2("val", "");
        $(this).select2();

    }
});

