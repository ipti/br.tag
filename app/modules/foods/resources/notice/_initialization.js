let data = [];
let table = $('table').DataTable({
    data: data,
    ordering: true,
    searching: false,
    paginate: true,
    language: getLanguagePtbr(),
    columnDefs: [
        {
            targets: -1, // Última coluna
            data: null,
            defaultContent: '<a class="delete-btn" style="color:#d21c1c; font-size:25px; cursor:pointer;"><span class="t-icon-trash"></span></a>'
        }
    ]
});
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

if(noticeID)  {
    $("#loading-popup").removeClass("hide");
    $.ajax({
        url: `?r=foods/foodnotice/getNotice&id=${noticeID}`,
        type: "GET"
    }).success(function(response){
        response = JSON.parse(response)
        $(".js-notice-name").val(response.name)
        $(".js-date").val(response.date)
        data = response.noticeItems
        table.destroy();
        table = $('table').DataTable({
            data: data,
            ordering:  true,
            searching: false,
            paginate: true,
            language: getLanguagePtbr(),
            columnDefs: [
                {
                  targets: -1,
                  data: null,
                  defaultContent: '<a class="delete-btn" style="color:#d21c1c; font-size:25px; cursor:pointer;"><span class="t-icon-trash"></span></a>'
                }
              ]
          });
        $("#loading-popup").removeClass("loading-center").addClass("hide");
    })
}
