var months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
var units = ["1º Bimestre", "2º Bimestre", "3º Bimestre", "4º Bimestre",
            "1ª Recuperação", "2ª Recuperação","3ª Recuperação", "4ª Recuperação", "Recuperação Final"];
var disciplines = [];
var $select = {};

function ini(){
    $chart.hide();
    $("#month").html("");
    $select = {};
    disciplines = [];
}

function loadClassroomInfos(results) {
    ini();
    var data = $.parseJSON(results);
    $.each(data, function(i, v) {
        disciplines[v.did] = v.discipline;
        if (!($select[v.month] instanceof Array)) $select[v.month] = [];
        $select[v.month].push(v.did);
        $.unique($select[v.month]);
    });

    var i = 0;
    $.each($select, function(m) {
        $("#month").append("<option value='" + m + "'>" + months[m] + "</option>");
        i++;
    });

    if (i > 1) $("#month").append("<option value='all'>Todos os meses</option>");

    $("#month").attr("data-placeholder", "Selecione um mês").val("").change().select2();
}
$(document).ready(function() {
    $('.filter-select').select2();

    $('#month').change(function() {
        $chart.hide();
        $("#discipline").html("");
        var month = $(this).val();
        if (month != null && month != "") {
            var disc =[];
            if(month == "all"){
                $.each($select, function(m, d) {
                    disc = disc.concat(d);
                });
                $.unique(disc);
            }else {
                disc = $select[month];
            }
            $.each(disc, function (j, d) {
                $("#discipline").append("<option value='" + d + "'>" + disciplines[d] + "</option>");
            });
            $.unique($("#discipline").children());
            if (disc.length > 1) $("#discipline").append("<option value='all'>Todas as Disciplinas</option>");

            $("#discipline").attr("data-placeholder", "Selecione uma disciplina").val("").change().select2();
        }
    });

    $('#discipline').change(function() {
        $chart.hide();
        var $cid = $("#classroom").val();
        var $mid = $("#month").val();
        var $did = $("#discipline").val();
        if ($did != null && $did != "") {
            configChart(chartDataUrl,$sid,$cid,$mid,$did);
        }
    });
});

$(window).load(function() {
    $("#classroom").attr("data-placeholder", "Selecione uma turma").val("").change().select2();
    $("#month").attr("data-placeholder", "Selecione um mês").select2();
    $("#discipline").attr("data-placeholder", "Selecione uma disciplina").select2();
});