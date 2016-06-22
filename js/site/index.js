$(document).on("click", ".load-more", function () {
    $.ajax({
        type: 'GET',
        url: loadMoreLogs,
        data: {
            date: $(".log-date").last().text()
        },
        success: function (data) {
            $(data).insertBefore(".load-more");
            if ($(".log").length >= $(".eggs").find(".widget").attr("total")) {
                $(".load-more").hide();
            }
            $(".log").each(function () {
                $(this).find(".glyphicons").html("<i></i>" + changeNameLength($(this).find(".glyphicons").html(), 100));
            });
        }
    });
});

$(document).ready(function () {
    $(".log").each(function () {
        $(this).find(".glyphicons").html("<i></i>" + changeNameLength($(this).find(".glyphicons").html(), 100));
    });

    loadLineChart(new Date().getFullYear());
});

function loadLineChart(year) {
    $.ajax({
        type: "POST",
        url: loadLineChartData,
        data: {
            year: year
        },
        success: function (data) {
            data = $.parseJSON(data);
            var chart = AmCharts.makeChart("lineChart", {
                "language": "pt",
                "type": "serial",
                "theme": "light",
                "legend": {
                    "useGraphSettings": true
                },
                "dataProvider": data,
                "synchronizeGrid": true,
                "valueAxes": [{
                    "id": "v1",
                    "axisColor": "black",
                    "axisThickness": 2,
                    "axisAlpha": 1,
                    "position": "left",
                    "integersOnly": true
                }],
                "graphs": [{
                    "valueAxis": "v1",
                    "lineColor": "#FF6600",
                    "bullet": "round",
                    "bulletBorderThickness": 1,
                    "hideBulletsCount": 30,
                    "title": "Escolas",
                    "valueField": "schools",
                    "fillAlphas": 0
                }, {
                    "valueAxis": "v2",
                    "lineColor": "#FCD202",
                    "bullet": "round",
                    "bulletBorderThickness": 1,
                    "hideBulletsCount": 30,
                    "title": "Turmas",
                    "valueField": "classrooms",
                    "fillAlphas": 0
                }, {
                    "valueAxis": "v3",
                    "lineColor": "#B0DE09",
                    "bullet": "round",
                    "bulletBorderThickness": 1,
                    "hideBulletsCount": 30,
                    "title": "Professores",
                    "valueField": "instructors",
                    "fillAlphas": 0
                }, {
                    "valueAxis": "v3",
                    "lineColor": "lightskyblue",
                    "bullet": "round",
                    "bulletBorderThickness": 1,
                    "hideBulletsCount": 30,
                    "title": "Alunos",
                    "valueField": "students",
                    "fillAlphas": 0
                }],
                "chartScrollbar": {},
                "chartCursor": {
                    "cursorPosition": "mouse"
                },
                "categoryField": "month",
                "categoryAxis": {
                    "parseDates": false,
                    "axisColor": "#DADADA",
                    "minorGridEnabled": true
                },
                "export": {
                    "enabled": true,
                    "position": "bottom-right"
                }
            });

            chart.addListener("dataUpdated", zoomChart);
            zoomChart();

            function zoomChart() {
                chart.zoomToIndexes(chart.dataProvider.length - 20, chart.dataProvider.length - 1);
            }
        }
    });
}