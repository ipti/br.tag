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


$('.js-datatable').DataTable({
    items,
    filter: true,
    paginate: true,
    language: {
        emptyTable: "Nenhum item cadastrado.",
        "sLoadingRecords": "Carregando...",
    }
});
