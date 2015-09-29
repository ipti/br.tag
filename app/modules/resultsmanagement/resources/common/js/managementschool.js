var months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
var disciplines = [];
var $select = {};

function loadClassroomInfos(results) {
    $("#chart_pie").hide();
    $("#month").html("");
    var data = $.parseJSON(results);
    $select = {};
    disciplines = [];
    $.each(data, function(i, v) {
        var monthId = v.month;
        var disciplineId = v.did;
        var disciplineName = v.discipline;

        disciplines[disciplineId] = disciplineName;
        if (!($select[monthId] instanceof Array))
            $select[monthId] = [];
        $select[monthId].push(disciplineId);
        $.unique($select[monthId]);
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
        $("#chart_pie").hide();
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
        $("#chart_pie").hide();
        var $cid = $("#classroom").val();
        var $mid = $("#month").val();
        var $did = $("#discipline").val();
        if ($did != null && $did != "") {
            $.getJSON(chartDataUrl,{sid:$sid,cid:$cid, mid:$mid, did:$did},function(json){
                var enrollments = json.enrollments;
                var classes = json.classes;
                var classesCount = classes.length;
                var faultsCount = 0;

                $.each(classes, function(i, $class){
                    faultsCount += parseInt($class.faults);
                });

                var faultsPercent = (faultsCount/(enrollments*classesCount) * 100).toFixed(2);
                var presencePercent = (100 - faultsPercent).toFixed(2);
                initChart([
                    { label: "Presença",  data: presencePercent },
                    { label: "Faltas",  data: faultsPercent },
                ]);
            });
        }
    });
});

$(window).load(function() {
    $("#classroom").attr("data-placeholder", "Selecione uma turma").val("").change().select2();
    $("#month").attr("data-placeholder", "Selecione um mês").select2();
    $("#discipline").attr("data-placeholder", "Selecione uma disciplina").select2();
});



function initChart(data){
    $("#chart_pie").show();

    $.plot("#chart_pie",[
            {label:"1º Bimestre", data:[[0,1],[1,7],[2,4],[3,3],[4,7],[5,19],[6,6],[7,2],[8,3],[9,1],[10,2]]} ,
            {label:"2º Bimestre", data:[[0,3],[1,2],[2,5],[3,7],[4,1],[5,3],[6,7],[7,5],[8,8],[9,10],[10,5]]},
            {label:"3º Bimestre", data:[[0,2],[1,4],[2,4],[3,7],[4,8],[5,7],[6,2],[7,3],[8,4],[9,3],[10,0]]},
            {label:"4º Bimestre", data:[[0,9],[1,8],[2,2],[3,10],[4,0],[5,16],[6,10],[7,9],[8,5],[9,6],[10,7]]}
        ],
        {
            series: {
                lines: {
                    show: true,
                    fill: true,
                },
                points: {
                    radius: 3,
                    fill: true,
                    show: true
                }
            },
            colors: [ "#496cad ", "#F08080"],
            xaxis: {
                axisLabel: "Notas",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10
            },
            yaxes: {
                axisLabel: "Nº de Alunos",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3,
                tickFormatter: function (v, axis) {
                    return v;
                }
            },
            legend: {
                noColumns: 1,
                labelBoxBorderColor: "#000000",
                position: "ne"
            },
            grid: {
                hoverable: true,
                borderWidth: 2,
                borderColor: "#633200",
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            },
        }

    );
    $("#chart_pie").UseTooltip();
    //$.plot($("#chart_pie"),
    //    data,{
    //    series: {
    //        pie: {
    //            show: true,
    //            highlight: {opacity: 0.1},
    //            radius: 1,
    //            stroke: {
    //                color: '#fff',
    //                width: 2
    //            },
    //            startAngle:2,
    //            combine: {
    //                color: '#353535',
    //                threshold: 0.05
    //            },
    //            label: {
    //                show: true,
    //                radius: 1,
    //                formatter: function(label, series){
    //                    return '<div class="label label-inverse">'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
    //                }
    //            }
    //        },
    //        grow: {	active: false}
    //    },
    //    colors: [ "#496cad ", "#F08080"],
    //    legend:{show:true},
    //    grid: {
    //        hoverable: true,
    //    },
    //    tooltip: true,
    //    tooltipOpts: {
    //        content: "%s : %y.1"+"%",
    //        shifts: {
    //            x: -30,
    //            y: -50
    //        },
    //        defaultTheme: true
    //    }});
};