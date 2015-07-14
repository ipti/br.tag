$('#classroom').change(function () {
    $.ajax({
        type: 'POST',
        url: getGradesUrl,
        cache: false,
        data: {classroom: $("#classroom").val()},
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data !== null && !$.isEmptyObject(data)) {
                $('.select2-container-active').removeClass('select2-container-active');
                $(':focus').blur();
                $(".students ul li").remove();
                $(".grades .tab-content .tab-pane").remove();
                $(".classroom").show();
                $.each(data, function (i, v) {
                    var name = i.length > 25 ? i.substring(0, 22) + "..." : i;
                    $(".classroom .students ul").append('<li>'
                            + '<a href="#tab' + v.enrollment_id + '" data-toggle="tab">'
                            + '<i></i><span class="grade-student-name">' + name + '</span>'
                            + '</a>'
                            + '</li>');
                    $(".grades .tab-content").append('<div class="tab-pane" id="tab' + v.enrollment_id + '">'
                            + '<h6>' + i + '</h6>'
                            + '<table class="grade-table table table-bordered table-striped">'
                            + '<col>'
                            + '<colgroup span="4"></colgroup>'
                            + '<colgroup span="4"></colgroup>'
                            + '<thead>'
                            + '<tr>'
                            + '<td rowspan="2"></td>'
                            + '<th colspan="4" scope="colgroup" class="center">Avaliações</th>'
                            + '<th colspan="5" scope="colgroup" class="center">Recuperações</th>'
                            + '</tr>'
                            + '<tr>'
                            + '<th class="center" scope="col">1ª</th>'
                            + '<th class="center" scope="col">2ª</th>'
                            + '<th class="center" scope="col">3ª</th>'
                            + '<th class="center" scope="col">4ª</th>'
                            + '<th class="center" scope="col">1ª</th>'
                            + '<th class="center" scope="col">2ª</th>'
                            + '<th class="center" scope="col">3ª</th>'
                            + '<th class="center" scope="col">4ª</th>'
                            + '<th class="center" scope="col">Final</th>'
                            + '</tr>'
                            + '</thead>'
                            + '<tbody class="row-grades"></tbody>'
                            + '</table>'
                            + '</div>');
                    var enrollment_id = v.enrollment_id;
                    $.each(v.disciplines, function (i, v) {
                        var discipline_id = i;
                        var tbody = "<tr>";
                        tbody += '<td class="discipline-name">' + v.name + '</td>';
                        var grades = [v.n1, v.n2, v.n3, v.n4, v.r1, v.r2, v.r3, v.r4, v.rf];
                        for (i = 0; i < grades.length; i++) {
                            //Cssar     
                            tbody += '<td class="center"><input name="grade[' + enrollment_id + '][' + discipline_id + '][' + i + ']" class="grade" type="number" step="0.1" min="0" max="10.0" value="' + grades[i] + '" /></td>"';
                        }
                        tbody += "</tr>";
                        $('#tab' + enrollment_id).find(".grade-table .row-grades").append(tbody);
                    });
                });
                $(".classroom .students li").first().addClass('active');
                $(".grades .tab-content .tab-pane").first().addClass('active');
            } else {
                $(".classroom").hide();
            }
        }
    });
});

$("#save").on("click", function () {
    $("#classes-form").submit();
});

$(document).on("keydown", function (e) {
//   var up = 38;
//   var down = 40;
//   if(e.keyCode === up){
//       $("li.active").prev().children("a[data-toggle]").click();
//   } else if(e.keyCode === down){
//       $("li.active").next().children("a[data-toggle]").click();
//   }
    if (e.keyCode === 13) {
        e.preventDefault();
        $("li.active").next().children("a[data-toggle]").click();
    }
});

$(document).on("keyup", "input.grade", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {

        var grade = /^(10|\d)(?:(\.|\,)\d{0,2}){0,1}$/;
        if (val.match(grade) === null) {
            val = "";
        } 
    }
    this.value = val;
});
