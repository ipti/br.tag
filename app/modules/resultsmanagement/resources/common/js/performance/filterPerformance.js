var unitsName = {"g1": "1º Bimestre", "g2": "2º Bimestre", "g3": "3º Bimestre", "g4": "4º Bimestre",
            "r1": "1ª Recuperação", "r2": "2ª Recuperação", "r3": "3ª Recuperação", "r4": "4ª Recuperação",
            "rf": "Recuperação Final"};
var disciplines = [];
var $select = {};


var $classroom = $("#classroom");
var $unit = $("#unit");
var $discipline = $("#discipline");

function ini(){
    $chart.hide();
    $unit.html("");
    $select = {};
    disciplines = [];
}

function loadClassroomInfos(results) {
    ini();
    var data = $.parseJSON(results);
    $.each(data, function(i, v) {
        disciplines[v.did] = v.discipline;
        if (!($select[v.unit] instanceof Array)) $select[v.unit] = [];
        $select[v.unit].push(v.did);
        $.unique($select[v.unit]);
    });

    var i = 0;
    $.each($select, function(u) {
        $unit.append("<option value='" + u + "'>" + unitsName[u] + "</option>");
        i++;
    });

    if (i > 1) {
        $unit.append("<option value='allG'>Todos Bimestres</option>");
        $unit.append("<option value='allR'>Todas Recuperações</option>");
        $unit.append("<option value='all'>Todos Bimestres e Recuperações</option>");
    }

    $unit.attr("data-placeholder", "Selecione um bimestre").val("").change().select2();
}
$(document).ready(function() {
    $('.filter-select').select2();

    $unit.change(function() {
        $chart.hide();
        $discipline.html("");
        var unit = $(this).val();
        if (unit != null && unit != "") {
            var disc =[];
            if(unit == "all"){
                $.each($select, function(m, d) {
                    disc = disc.concat(d);
                });
                $.unique(disc);
            }else if(unit == "allG"){
                disc = disc.concat($select["g1"]);
                disc = disc.concat($select["g2"]);
                disc = disc.concat($select["g3"]);
                disc = disc.concat($select["g4"]);
                $.unique(disc);
            }else if(unit == "allR"){
                disc = disc.concat($select["r1"]);
                disc = disc.concat($select["r2"]);
                disc = disc.concat($select["r3"]);
                disc = disc.concat($select["r4"]);
                disc = disc.concat($select["rf"]);
                $.unique(disc);
            }else {
                disc = $select[unit];
            }
            $.each(disc, function (j, d) {
                $("#discipline").append("<option value='" + d + "'>" + disciplines[d] + "</option>");
            });
            $.unique($discipline.children());
            if (disc.length > 1) $discipline.append("<option value='all'>Todas as Disciplinas</option>");

            $discipline.attr("data-placeholder", "Selecione uma disciplina").val("").change().select2();
        }
    });

    $('#discipline').change(function() {
        $chart.hide();
        var $cid = $classroom.val();
        var $bid = $unit.val();
        var $did = $discipline.val();
        if ($did != null && $did != "") {
            if($bid == "allG")  $bid = "'g1', 'g2', 'g3', 'g4'";
            else if($bid == "allR")  $bid = "'r1', 'r2', 'r3', 'r4', 'rf'";
            else if($bid == "all")  $bid = "'g1', 'g2', 'g3', 'g4','r1', 'r2', 'r3', 'r4', 'rf'";
            else $bid = "'"+$bid+"'";
            configChart(chartDataUrl,$sid,$cid,$bid,$did);
        }
    });
});

$(window).load(function() {
    $classroom.attr("data-placeholder", "Selecione uma turma").val("").change().select2();
    $unit.attr("data-placeholder", "Selecione um bimestre").val("").change().select2();
    $discipline.attr("data-placeholder", "Selecione uma disciplina").val("").change().select2();
});