var $chart = $("#chart");

function configChart(url,$sid, $cid, $mid, $did){
    $.getJSON(url,{sid:$sid,cid:$cid, mid:$mid, did:$did},function(json){
        var enrollments = json.enrollments;
        var classes = json.classes;
        var classesCount = classes.length;
        var faultsCount = 0;

        $.each(classes, function(i, $class){
            faultsCount += parseInt($class.faults);
        });
		console.log(faultsCount);
        var faultsPercent = (faultsCount/(enrollments*classesCount) * 100).toFixed(2);
        var presencePercent = (100 - faultsPercent).toFixed(2);
        initChart([
            { label: "Presença",  data: presencePercent },
            { label: "Faltas",  data: faultsPercent },
        ]);
    });
}

function initChart(data){
    $chart.show();
    $.plot($chart,
        data,{
        series: {
            pie: {
                show: true,
                highlight: {opacity: 0.1},
                radius: 1,
                stroke: {
                    color: '#fff',
                    width: 2
                },
                startAngle:2,
                combine: {
                    color: '#353535',
                    threshold: 0.05
                },
                label: {
                    show: true,
                    radius: 1,
                    formatter: function(label, series){
                        return '<div class="label label-inverse">'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
                    }
                }
            },
            grow: {	active: false}
        },
        colors: [ "#496cad ", "#F08080"],
        legend:{show:true},
        grid: {
            hoverable: true,
        },
        tooltip: true,
        tooltipOpts: {
            content: "%s : %y.1"+"%",
            shifts: {
                x: -30,
                y: -50
            },
            defaultTheme: true
        }});
};