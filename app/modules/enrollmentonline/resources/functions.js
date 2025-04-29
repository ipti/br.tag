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
