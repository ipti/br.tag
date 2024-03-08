$('.js-date').mask("99/99/9999");

$(".js-date").datepicker({
  language: "pt-BR",
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true,
  allowInputToggle: true,
  disableTouchKeyboard: true,
  keyboardNavigation: false,
  orientation: "bottom left",
  clearBtn: true,
  maxViewMode: 2,
  showClearButton: false
});

$('select.js-initialize-select2').select2();

$.ajax({
  url: "?r=foods/foodnotice/getTacoFoods",
  type: "GET",
}).success(function (response) {
  let foods = JSON.parse(response);
  let select = $("select.js-taco-foods")
  $.map(foods, function (name, id) {
    name = name.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
    select.append($('<option>', {
      value: id,
      text: name
    }));
  });
})