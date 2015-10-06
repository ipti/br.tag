var disciplines = [];
var $select = {};

var $proficiencyClassroom = $("#proficiency-classroom");
var $proficiencyDiscipline = $("#proficiency-discipline");

var $proficiencies = $("#proficiencies");

function ini(){
    $proficiencies.html("");
    $proficiencyDiscipline.html("");
    $select = {};
    disciplines = [];
}

function loadClassroomInfos(results) {
    ini();
    var data = $.parseJSON(results);
    $.each(data.disciplines, function(i, v) {
        disciplines[v.did] = v.discipline;
        if (!($select[v.did] instanceof Array)) $select[v.did] = [];
        $select[v.did].push(v.did);
        $.unique($select[v.did]);
    });

    var i = 0;
    $.each($select, function(u) {
        $proficiencyDiscipline.append("<option value='" + u + "'>" + disciplines[u] + "</option>");
        i++;
    });

    if (i > 1) {
        $proficiencyDiscipline.append("<option value='all'>Todas as disciplinas</option>");
    }
    $proficiencyDiscipline.attr("data-placeholder", "Selecione uma disciplina").val("").select2();

}


$(document).on("change", "#proficiency-discipline", function(){
    var $cid = $proficiencyClassroom.val();
    var $did = $proficiencyDiscipline.val();
    $.getJSON(proficiencyDataUrl,{sid:$sid,cid:$cid, did:$did},function(json) {
        var $html = "";

        $.each(json, function(i,v){
            var percent = function (a, b) {
                return Math.ceil((a/b)*10000)/100;
            }
            var year = i;
            var $proficiency = v;
            var all = $proficiency.best + $proficiency.good + $proficiency.regular + $proficiency.bad;
            var bestPercent = percent($proficiency.best, all);
            var goodPercent = percent($proficiency.good, all);
            var regularPercent = percent($proficiency.regular, all)
            var badPercent = percent($proficiency.bad, all);
            var color = bestPercent >= goodPercent && bestPercent >= regularPercent && bestPercent >= badPercent ? "green" : "red";
                color = goodPercent > bestPercent && goodPercent >= regularPercent && goodPercent >= badPercent ? "blue" : color;
                color = regularPercent > bestPercent && regularPercent > goodPercent && regularPercent >= badPercent ? "yellow" : color;

            $html += "<div class='col-md-4'>"
                + "<h5>" + year + "</h5>"
                + "   <div class='proficiency-box proficiency-box-"+color+"'>"
                + "      <div class='pull-left proficiency-box-percentage'>"
                + "          <span>" + bestPercent + "%</span>"
                + "      </div>"
                + "      <div class='proficiency-box-label'>"
                + "        <span>Avançado (" + $proficiency.best + " alunos)<br>"
                + "            <span>Além da expectativa</span>"
                + "        </span>"
                + "      </div>"
                + "      <div class='separator bottom'></div>"
                + "      <div class='pull-left proficiency-box-percentage'>"
                + "          <span>" + goodPercent + "%</span >"
                + "      </div>"
                + "      <div class='proficiency-box-label'>"
                + "          <span>Proficiente (" + $proficiency.good + " alunos)<br>"
                + "              <span>Aprendizado esperado</span>"
                + "          </span>"
                + "      </div>"
                + "      <div class='separator bottom'></div>"
                + "      <div class='pull-left proficiency-box-percentage'>"
                + "          <span>" + regularPercent + "%</span >"
                + "      </div>"
                + "      <div class='proficiency-box-label'>"
                + "          <span>Básico (" + $proficiency.regular + " alunos)<br>"
                + "              <span>Pouco Aprendizado</span>"
                + "          </span>"
                + "      </div>"
                + "      <div class='separator bottom'></div>"
                + "          <div class='pull-left proficiency-box-percentage'>"
                + "              <span>" + badPercent + "%</span >"
                + "          </div>"
                + "          <div class='proficiency-box-label'>"
                + "              <span>Insuficiente (" + $proficiency.bad + " alunos)<br>"
                + "                  <span>Pouco Aprendizado</span>"
                + "              </span>"
                + "          </div>"
                + "      </div>"
                + "      <div class='separator bottom'></div>"
                + "  </div>"
                + "</div>";


        });
        $proficiencies.html($html);
    });

});




$(document).ready(function() {
    $('.filter-select').select2();
});

$(window).load(function() {
    $proficiencyClassroom.attr("data-placeholder", "Selecione uma turma").val("").select2();
    $proficiencyDiscipline.attr("data-placeholder", "Selecione uma disciplina").val("").select2();
});