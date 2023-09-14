alert("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
$('.js-date').mask("99/99/9999");

// var currentDate = new Intl.DateTimeFormat('pt-BR').format(Date.now());
$(".js-date").datepicker({
    locate: "pt-BR",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    allowInputToggle: true,
    disableTouchKeyboard: true,
    keyboardNavigation: false,
    orientation: "bottom left",
    clearBtn: true,
    maxViewMode: 2,
    startDate: "01/01/" + $(".school-year").val(),
    endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev, indirect) {
   
})

// $(".js-date").datepicker("setDate", currentDate);