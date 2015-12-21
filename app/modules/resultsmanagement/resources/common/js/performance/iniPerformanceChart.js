/**
 * Created by IPTIPC002 on 30/09/2015.
 */
var $chart = $("#chart");
var myPlot;

function configChart(url,$sid, $cid, $bid, $did){
    $.getJSON(url,{sid:$sid,cid:$cid, bid:$bid, did:$did},function(json){
        var grades = json.grades;

        var units = {};
        $.each(grades, function(i, $grade) {
            if(!(units[$grade.bimester] instanceof Array)) {
                units[$grade.bimester] = [];
                for(var i = 0; i<=10; i++){
                    units[$grade.bimester][i] = 0;
                }
            }
            if($grade.grade != null)
                units[$grade.bimester][$grade.grade]++;
        });

        var $params = [];
        var i = 0;
        $.each(units, function(bid, $grades){
            $data = [];
            $($grades).each(function(n,c){
                $data.push([n,c]);
            });
            var $param = {label: unitsName[bid], data: $data, idx: i++};
            $params.push($param);
        });
        initChart($params);
    });
}

function togglePlot(seriesIdx){
    var someData = myPlot.getData();
    someData[seriesIdx].lines.show = !someData[seriesIdx].lines.show;
    myPlot.setData(someData);
    myPlot.draw();
}

$(document).on("change",".linesCheckbox", function(){
    togglePlot($(this).val());
});



function initChart(data){
    $chart.show();
    myPlot = $.plot($chart, data,
        {
            series: {
                lines: {
                    show: true,
                    fill: true,
                }
            },
            colors: [ "#27be25 ", "#519ae1", "#E4842A", "#f3d442", "#D577FF", "#e20000", "#878787", "#b1591a", "#3c1b0e"],
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
                labelFormatter: function(label, series){
                    return '&nbsp;<input type="checkbox" class="linesCheckbox" checked name="lines" value="'+series.idx+'">'+label+'</input>';
                },
                noColumns: 1,
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
};