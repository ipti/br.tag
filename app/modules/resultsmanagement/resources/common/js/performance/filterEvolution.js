var unitsName = {
    "g1": "1º Bimestre", "g2": "2º Bimestre", "g3": "3º Bimestre", "g4": "4º Bimestre",
    "r1": "1ª Recuperação", "r2": "2ª Recuperação", "r3": "3ª Recuperação", "r4": "4ª Recuperação",
    "rf": "Recuperação Final"
};
var disciplines = [];

var $select = {};

var $evolutionClassroom = $("#evolution-classroom");
var $evolutionDiscipline = $("#evolution-discipline");

var $evolutions = $("#evolutions");

function ini() {
    $evolutions.html("");
    $evolutionDiscipline.html("");
    $select = {};
    disciplines = [];
}


function loadDisciplineInfo(results) {
    ini();
    var data = $.parseJSON(results);
    $.each(data.disciplines, function (i, v) {
        disciplines[v.did] = v.discipline;
        if (!($select[v.did] instanceof Array)) $select[v.did] = [];
        $select[v.did].push(v.did);
        $.unique($select[v.did]);
    });

    var i = 0;
    $.each($select, function (u) {
        $evolutionDiscipline.append("<option value='" + u + "'>" + disciplines[u] + "</option>");
        i++;
    });

    if (i > 1) {
        $evolutionDiscipline.append("<option value='all'>Todas as disciplinas</option>");
    }
    $evolutionDiscipline.attr("data-placeholder", "Selecione uma disciplina").val("").select2();

}

$(document).on("change", "#evolution-discipline", function () {
    var $cid = $evolutionClassroom.val();
    var $did = $evolutionDiscipline.val();
    $evolutions.html("");
    $.getJSON(evolutionDataUrl, {sid: $sid, cid: $cid, did: $did}, function (json) {
        //json = {'g1': 12.5, 'g2': 10, 'g3': 25, 'g4': 13};
        var j = 1;
        var prev = null;
        $.each(json, function (i, v) {
            var percent = v;
            var bimester = unitsName[i];
            var color = percent >= 75 ? "box-green-1" : "box-red-1";
            color = percent < 75 && percent >= 50 ? "box-blue-1" : color;
            color = percent < 50 && percent >= 25 ? "box-yellow-1" : color;

            if (prev){
                $("#efficiency-" + (j - 1) + " .efficiency-number").append((percent - prev).toFixed(1));
                $("#efficiency-" + (j - 1) + " .efficiency-number-label").append("pontos percentuais");
            };

            $evolutions.append("<div class='col-md-3'>"
                + "<h5>" + bimester + "</h5>"
                + "<div class='efficiency-box box " + color + "'>"
                + percent + "%"
                + "</div>"
                + "<div class='efficiency-label' id='efficiency-" + j + "'><span class='efficiency-number'></span><span class='efficiency-number-label'></span></div>"
                + "</div>");
            prev = percent;
            j++;
        });
        $("#efficiency-" + (j - 1) + " .efficiency-number-label").append("...");
    });

});


$(document).ready(function () {
    $('.filter-select').select2();
});

$(window).load(function () {
    $evolutionClassroom.attr("data-placeholder", "Selecione uma turma").val("").select2();
    $evolutionDiscipline.attr("data-placeholder", "Selecione uma disciplina").val("").select2();
});
