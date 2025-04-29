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
    if($(".js-filiation-select").select2("val") === "1") {
        $(".js-hide-filiation").css("display", "flex");
    } else {
        $(".js-hide-filiation").hide()
    }
});

$(".js-uf").on("change", ()=>{

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/getCities",
        cache: false,
        data: {
            state: $('select.js-uf').val(),
        },
        success: function (response) {
            data = DOMPurify.sanitize(response);

            $("select.js-cities").html(response)
            $("select.js-cities").select2()
            $("select.js-cities").removeAttr("disabled");
        }
    })
})
