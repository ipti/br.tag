const params = new URLSearchParams(document.location.search)
let classroomFk = params.get("classroom_fk")
let discipliceFk = params.get("discipline_fk")
$.ajax({
    type: "POST",
    url: "?r=classdiary/default/getMonths",
    cache: false,
    data: {
        classroom: classroomFk,
    },
    beforeSend: function () {
        $(".loading-frequency").css("display", "inline-block");
        $(".js-months").attr("disabled", "disabled");
    },
    success: function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $(".js-months").children().remove();
            $(".js-months").append(new Option("Selecione o Mês/Ano", ""));
            $.each(data.months, function (index, value) {
                $(".js-months").append(new Option(value.name, value.id));
            });
            $(".js-months option:first").attr("selected", "selected").trigger("change.select2");
            $('.js-months').select2()
        }
    },
    complete: function (response) {
        $(".loading-frequency").hide();
        $(".js-months").removeAttr("disabled");
    },
});

$("select.js-months").on("change", function (){
  $.ajax({
    type: "POST",
    url:"?r=classdiary/default/getDates",
    data: {
        year: $(this).val().split('-')[0],
        month: $(this).val().split('-')[1],
        classroom: classroomFk,
        disciplice: discipliceFk,
    },
    success: function (data) {
        let selectedOptionText = $("select.js-months option:selected").text();

        console.log(selectedOptionText);
        const result = JSON.parse(data);
        const daysCardsContainer = $(".js-days-cards");
        let  daysCards = result["scheduleDays"].reduce((acc, element) =>
             acc += `<div class="column clearfix no-grow">
                <a href="" class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary t-margin-none--left">${selectedOptionText}</div>
                        <div class="t-cards-title">${element["date"]}</div>
                        <div class="t-cards-text clear-margin--left">${element["week_day"]}</div>
                    </div>
                </a>
            </div>`,'');
            daysCards = result["scheduleDays"].reduce((acc, element) =>
            acc += `
            <div class="column clearfix no-grow">
                <a href="" class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary t-margin-none--left">${selectedOptionText}</div>
                        <div class="t-cards-title">${element["date"]}</div>
                        <div class="t-cards-text clear-margin--left">${element["week_day"]}</div>
                    </div>
                </a>
            </div>`, "");
            daysCardsContainer.html(daysCards);
    }
  })
})
