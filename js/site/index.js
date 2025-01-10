$(document).on("click", ".info-list", function () {
    $.ajax({
        type: "GET",
        url: loadMoreLogs,
        data: {
            date: $(".log-date").last().text(),
        },
        success: function (data) {
            $(".no-recent-activitive").remove();
            $(data).insertBefore(".info-list");
            if (
                $(".log").length >= $(".eggs").find(".log-widget").attr("total")
            ) {
                $(".info-list").hide();
            }
            $(".log").each(function () {
                $(this)
                    .find(".t-info_positive")
                    .html(
                        "<i></i>" +
                            changeNameLength(
                                $(this).find(".t-info_positive").text(),
                                100
                            )
                    );
            });
        },
    });
});

let visibleWarnings = 1;
$(document).on("click", ".warn-list", function () {
    visibleWarnings++;
    getWarnsHtml({ limit: 8 * visibleWarnings });
});

$(function () {
    $(".log").each(function () {
        $(this)
            .find(".glyphicons")
            .html(
                "<i></i>" +
                    changeNameLength($(this).find(".glyphicons").text(), 100)
            );
    });

    if ($(".logs").length >= $(".eggs").find(".log-widget").attr("total")) {
        $(".info-list").hide();
    }

    if ($(".board-msg").attr("version") !== getCookie("tag_version")) {
        $(".board-msg").show();
    }

    if (
        $(".warn-div").length >= $(".eggs").find(".warn-widget").attr("total")
    ) {
        $(".warn-list").hide();
    }

    if ($(".board-msg").attr("version") !== getCookie("tag_version")) {
        $(".board-msg").show();
    }

    getWarnsHtml();

    // loadLineChart(new Date().getFullYear());
    // loadCylinderChart(new Date().getFullYear());
    // loadPieChart(new Date().getFullYear());
});

function loadPieChart(year) {
    $.ajax({
        type: "POST",
        url: loadPieChartData,
        data: {
            year: year,
        },
        success: function (data) {
            data = $.parseJSON(data);
            var chart = AmCharts.makeChart("pieChart", {
                language: "pt",
                type: "pie",
                theme: "light",
                dataProvider: [
                    {
                        country: "Matriculados",
                        value: data.enrollments,
                    },
                    {
                        country: "Não-matriculados",
                        value: data.students - data.enrollments,
                    },
                ],
                valueField: "value",
                titleField: "country",
                outlineAlpha: 0.4,
                depth3D: 15,
                balloonText:
                    "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                angle: 30,
                export: {
                    enabled: true,
                },
            });
            $(".pie-chart-input")
                .off()
                .on("input change", function () {
                    var property = jQuery(this).data("property");
                    var target = chart;
                    var value = Number(this.value);
                    chart.startDuration = 0;

                    if (property == "innerRadius") {
                        value += "%";
                    }

                    target[property] = value;
                    chart.validateNow();
                });
        },
    });
}

function loadCylinderChart(year) {
    $.ajax({
        type: "POST",
        url: loadCylinderChartData,
        data: {
            year: year,
        },
        success: function (data) {
            data = $.parseJSON(data);
            var chart = AmCharts.makeChart(
                "cylinderChart",
                {
                    theme: "light",
                    type: "serial",
                    startDuration: 2,
                    dataProvider: [
                        {
                            category: "Escolas",
                            quantity: data.schools,
                            color: "#FF6600",
                        },
                        {
                            category: "Turmas",
                            quantity: data.classrooms,
                            color: "#FCD202",
                        },
                        {
                            category: "Professores",
                            quantity: data.instructors,
                            color: "#04D215",
                        },
                        {
                            category: "Alunos",
                            quantity: data.students,
                            color: "#0D8ECF",
                        },
                    ],
                    valueAxes: [
                        {
                            position: "left",
                            axisAlpha: 0,
                            gridAlpha: 0,
                        },
                    ],
                    graphs: [
                        {
                            balloonText: "[[category]]: <b>[[value]]</b>",
                            colorField: "color",
                            fillAlphas: 0.85,
                            lineAlpha: 0.1,
                            type: "column",
                            topRadius: 1,
                            valueField: "quantity",
                        },
                    ],
                    depth3D: 40,
                    angle: 30,
                    chartCursor: {
                        categoryBalloonEnabled: false,
                        cursorAlpha: 0,
                        zoomable: false,
                    },
                    categoryField: "category",
                    categoryAxis: {
                        gridPosition: "start",
                        axisAlpha: 0,
                        gridAlpha: 0,
                    },
                    export: {
                        enabled: true,
                    },
                },
                0
            );

            $(".cylinder-chart-input")
                .off()
                .on("input change", function () {
                    var property = jQuery(this).data("property");
                    var target = chart;
                    chart.startDuration = 0;

                    if (property == "topRadius") {
                        target = chart.graphs[0];
                    }

                    target[property] = this.value;
                    chart.validateNow();
                });
        },
    });
}

function loadLineChart(year) {
    $.ajax({
        type: "POST",
        url: loadLineChartData,
        data: {
            year: year,
        },
        success: function (data) {
            data = $.parseJSON(data);
            var chart = AmCharts.makeChart("lineChart", {
                language: "pt",
                type: "serial",
                theme: "light",
                legend: {
                    useGraphSettings: true,
                },
                dataProvider: data,
                synchronizeGrid: true,
                valueAxes: [
                    {
                        id: "v1",
                        axisColor: "black",
                        axisThickness: 2,
                        axisAlpha: 1,
                        position: "left",
                        integersOnly: true,
                    },
                ],
                graphs: [
                    {
                        valueAxis: "v1",
                        lineColor: "#FF6600",
                        bullet: "round",
                        bulletBorderThickness: 1,
                        hideBulletsCount: 30,
                        title: "Escolas",
                        valueField: "schools",
                        fillAlphas: 0,
                    },
                    {
                        valueAxis: "v2",
                        lineColor: "#FCD202",
                        bullet: "round",
                        bulletBorderThickness: 1,
                        hideBulletsCount: 30,
                        title: "Turmas",
                        valueField: "classrooms",
                        fillAlphas: 0,
                    },
                    {
                        valueAxis: "v3",
                        lineColor: "#B0DE09",
                        bullet: "round",
                        bulletBorderThickness: 1,
                        hideBulletsCount: 30,
                        title: "Professores",
                        valueField: "instructors",
                        fillAlphas: 0,
                    },
                    {
                        valueAxis: "v3",
                        lineColor: "lightskyblue",
                        bullet: "round",
                        bulletBorderThickness: 1,
                        hideBulletsCount: 30,
                        title: "Alunos",
                        valueField: "students",
                        fillAlphas: 0,
                    },
                ],
                chartScrollbar: {},
                chartCursor: {
                    cursorPosition: "mouse",
                },
                categoryField: "month",
                categoryAxis: {
                    parseDates: false,
                    axisColor: "#DADADA",
                    minorGridEnabled: true,
                },
                export: {
                    enabled: true,
                    position: "bottom-right",
                },
            });

            chart.addListener("dataUpdated", zoomChart);
            zoomChart();

            function zoomChart() {
                chart.zoomToIndexes(
                    chart.dataProvider.length - 20,
                    chart.dataProvider.length - 1
                );
            }
        },
    });
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function getWarnsHtml({ limit = 8 } = {}) {
    $.ajax({
        url: loadWarns, // Substitua pela URL correta
        type: "GET",
        data: { limit: limit },
        beforeSend: function () {
            console.log("Enviando...");
        },
        success: function (response) {
            $("#warns").html(response);
        },
        error: function () {
            alert("Erro ao carregar dados!");
        },
    });
}
