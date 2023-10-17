var correctIntervalDate = false;

$('.auditory-initial-date, .auditory-final-date').mask("99/99/9999");
$(".auditory-initial-date").datepicker({
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
    startDate: "01/01/" + $(".school-year").val(),
    endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev, indirect) {
    if ($(".auditory-initial-date").val() !== "" && $(".auditory-initial-date").val().length == 10
        && $(".auditory-final-date").val() !== "" && $(".auditory-final-date").val().length == 10) {
        var startDateStr = $(".auditory-initial-date").val().split("/");
        var startDate = !indirect ? new Date(ev.date.getFullYear(), ev.date.getMonth(), ev.date.getDate(), 0, 0, 0) : new Date(startDateStr[2], startDateStr[1] - 1, startDateStr[0], 0, 0, 0);
        var endDateStr = $(".auditory-final-date").val().split("/");
        var endDate = new Date(endDateStr[2], endDateStr[1] - 1, endDateStr[0], 0, 0, 0);
        if (endDate < startDate) {
            correctIntervalDate = false;
        } else {
            correctIntervalDate = true;
        }
    } else {
        correctIntervalDate = false;
    }
}).on('clearDate', function (ev) {
    correctIntervalDate = false;
});

$(".auditory-final-date").datepicker({
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
    startDate: "01/01/" + $(".school-year").val(),
    endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev) {
    if ($(".auditory-initial-date").val() !== "" && $(".auditory-initial-date").val().length == 10
        && $(".auditory-final-date").val() !== "" && $(".auditory-final-date").val().length == 10) {
        var endDate = new Date(ev.date.getFullYear(), ev.date.getMonth(), ev.date.getDate(), 0, 0, 0);
        var startDateStr = $(".auditory-initial-date").val().split("/");
        var startDate = new Date(startDateStr[2], startDateStr[1] - 1, startDateStr[0], 0, 0, 0);
        if (endDate < startDate) {
            correctIntervalDate = false;
        } else {
            correctIntervalDate = true;
        }
    } else {
        correctIntervalDate = false;
    }
}).on('clearDate', function (ev) {
    correctIntervalDate = false;
});

$(document).on("click", "#loadtable", function () {
    $(".alert-required-fields").hide();
    if (correctIntervalDate) {
        if ($.fn.DataTable.isDataTable('.auditory-table')) {
            table.ajax.reload(null, true);
        } else {
            initTable();
        }
        $(".auditory-table-container").show();
    } else {
        $(".alert-required-fields").show();
        $(".auditory-table-container").hide();
    }
});

var table;
function initTable() {
    table = $(".auditory-table").DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            type: "POST",
            url: "/index.php?r=admin/getAuditoryLogs",
            data: function (data) {
                data.school = $("#log-school").val();
                data.action = $("#log-action").val();
                data.user = $("#log-user").val();
                data.initialDate = $(".auditory-initial-date").val();
                data.finalDate = $(".auditory-final-date").val();
            },
            beforeSend: function () {
                $(".auditory-table").css("opacity", "0.4");
                $('.loading-auditory').css("display", "inline-block");
                $("#loadTable").addClass("disabled");
                $(".dataTables_processing").hide();
            },
            complete: function (data) {
                $(".auditory-table").css("opacity", "1");
                $('.loading-auditory').hide();
                $("#loadTable").removeClass("disabled");
            }
        },
        autoWidth: false,
        sorting: [[4, "desc"]],
        fixedColumns: true,
        columns: [
            {"data": "school"},
            {"data": "user"},
            {"data": "action", "bSortable": false},
            {"data": "event", "bSortable": false},
            {"data": "date"},
        ],
        language: {
            "sEmptyTable": "Nenhum registro encontrado.",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado.",
            "sSearch": "Pesquisar <i class='fa fa-question-circle search-info' data-toggle='tooltip' data-placement='bottom' title='Para pesquisar por múltiplas informações simultaneamente, basta separar as palavras-chave utilizando a tecla de ESPAÇO.'></i>",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
    });
}