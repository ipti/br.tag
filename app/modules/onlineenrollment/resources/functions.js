$(".js-cpf-mask").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});

$(".js-filiation-select").on("change", () => {
    if($(".js-filiation-select").select2("val") === "1") {
        $(".js-hide-filiation").css("display", "flex");
    } else {
        $(".js-hide-filiation").hide()
    }
});
