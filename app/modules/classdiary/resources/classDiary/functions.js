
var widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
renderFrequencyElement(widthWindow)

/* window.addEventListener('resize', function() {
     widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    renderFrequencyElement(widthWindow)
    console.log(widthWindow)
}); */

function renderFrequencyElement(w) {
    const urlParams = new URLSearchParams(window.location.search);
    const classrom_fk = urlParams.get("classrom_fk")
    const stage_fk = urlParams.get("stage_fk")
    const discipline_fk = urlParams.get("discipline_fk")
    const date = $('.js-date').val()
    const url = w <= 640 ?  `RenderFrequencyElementMobile` : `RenderFrequencyElementDesktop`;
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/${url}&classrom_fk=${classrom_fk}&stage_fk=${stage_fk}&discipline_fk=${discipline_fk}&date=${date}`,
        type: "GET",
        
    }).success(function (response) {
        $(".js-frequency-element").html(response)
    });  
}

$(document).on("change", ".js-frequency-checkbox", function () {
    $.ajax({
        type: "POST",
        url: `${window.location.host}?r=classdiary/default/saveFresquency`,
        cache: false,
        data: {
            classroom_id: $(this).attr("classrom_id"),
            date: $(".js-date").val(),
            schedule: $(this).attr("schedule"),
            studentId: $(this).attr("studentId"),
            fault: $(this).is(":checked") ? 1 : 0,
            stage_fk: $(this).attr("stage_fk")
        },
        beforeSend: function () {
            $(".js-table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $(".js-date, .js-change-date").attr("disabled", "disabled");
        },
        complete: function (response) {
            $(".js-table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $(".js-date, .js-change-date").removeAttr("disabled");
        },
    })
});


$(".js-change-date").on("click", function () {
    renderFrequencyElement(widthWindow)
});

$(document).on("click", ".js-justification", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const classrom_fk = urlParams.get("classrom_fk")
    const stage_fk = urlParams.get("stage_fk")
    const date = $('.js-date').val()
    console.log($(this).val());
    return;
    // checkbox atributes
    const checkbox = $(this).parent().parent().children("td").children();
    $.ajax({
        type:"POST",
        url: `${window.location.host}/?r=classdiary/default/StudentClassDiary`,
        data:  {
            name: $(this).innerHTML,
            classroomId: classrom_fk,
            stageFk: stage_fk,
            date : date,
            schedule: checkbox.attr("schedule"),
            studentId :checkbox.attr("studentId")
        }
    })
});
