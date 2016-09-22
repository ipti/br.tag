var students_array = new Array();
var index = 0;
var classdays = '';
var weekly_schedule =  new Array();
var special_days = new Array();
var saturdays = new Array();

function checkSpecialDay(date, specialdays){
    var count = 0;
    $(specialdays).each(function (i, period) {
        var start_date = new Date(period['start_date']);
        var end_date = new Date(period['end_date']);

       if(start_date.getTime() <= date.getTime() && end_date.getTime() >= date.getTime()){
           count++;
       }
    });
    return count > 0;
}

function checkSaturdaySchool(date, saturdays){
    $(saturdays).each(function (i, period) {
        var start_date = new Date(period['start_date']);
        var end_date = new Date(period['end_date']);
        if(start_date.getTime() <= date.getTime() && end_date.getTime() >= date.getTime()){
            return true;
        }
    });
    return false;
}

function checkNoSchool(day, no_school_days) {
    var count = 0;
    $(no_school_days).each(function (i, days) {
        var no_school_day = days['day'];
        if(day == no_school_day){
            count++;
        }
    });
    return count > 0;
}

function  adicionaProfessorFundamental(special_days, no_school) {

    var thead = '';

    index = 0;
    classdays = '';
    var month = $('#month').val();

    var year = new Date().getFullYear();

    var maxDays = new Date(year, month, 0).getDate();
    thead += "<th><span>Professor</span></th>";
    for (var day = 1; day <= maxDays; day++){
        var date = new Date(month+" "+day+" "+year);

        var weekDay = date.getDay();

        if (weekDay != 0){

            if(weekDay == 6 && checkSaturdaySchool(date, saturdays)){
                thead += '<th><span>';
                thead += day;
                thead += '<input id="instructor_day['+day+']" name="instructor_day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;">';
                thead += '</span></th>';
            }
            if(weekDay < 6){
                var disabled = '';
                var checked = '';
                console.log(checkSpecialDay(date, special_days));
                if ((checkSpecialDay(date, special_days))  ) {
                    checked = ' checked ';
                    disabled = ' disabled ';
                }
                if (checkNoSchool(day, no_school)) {
                    checked = ' checked ';
                }
                thead += '<th><span>';
                thead += day;
                thead += '<input id="instructor_day['+day+']" name="instructor_day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+checked+disabled+'>';
                thead += '</span></th>';
            }
        }

    }
    return thead;

}

function adicionaNivelFundamental(special_days, no_school) {

    var thead = adicionaProfessorFundamental(special_days, no_school);

    $('#frequency > thead > tr').append(thead);
    var tbody_td = '';

}


function addStudentForward() {
    index++;
    if (index == students_array.length - 1) {

        $('#next-student-button').remove();
    }
    $('#frequency > tbody').html('');
    var tbody = "";
    tbody += '<tr>';
    tbody += "<td><span>" + students_array[index]['name'] + "</span></td>"
    tbody += classdays
    tbody += '</tr>';
    $('#frequency > tbody').append(tbody);

    if (index == 1 && index <= students_array.length) {
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>").insertBefore('#next-student-button')
        $('#previous-student-button').click(function() {
            addStudentBackward();
        });
    }
}

function  addStudentBackward() {
    if (index == (students_array.length - 1) && index > 0) {

        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>").insertAfter('#previous-student-button')
        $('#next-student-button').click(function() {
            addStudentForward();
        });
    }
    index--;

    if (index == 0) {
        $('#previous-student-button').remove();
    }



    $('#frequency > tbody').html('');
    var tbody = "";
    tbody += '<tr>';
    tbody += "<td><span>" + students_array[index]['name'] + "</span></td>"
    tbody += classdays
    tbody += '</tr>';
    $('#frequency > tbody').append(tbody);
}


$('#classesSearch').on('click', function(){
    console.log(jQuery('#classroom').parents("form").serialize());
    jQuery.ajax({
        'type':'GET',
        'url':getClassesURL,
        'cache':false,
        'data':jQuery('#classroom').parents("form").serialize(),
        'success':function(data){
            var data = jQuery.parseJSON(data);
            console.log(data);
            if(data['days'] == undefined) {
                // $('#frequency > thead').html('<tr><th class="center">Não há aulas desta matéria.</th></tr>');
                // $('#frequency > tbody').html('');
                $('#widget-frequency').show();
                $('#frequency').show();
                return true;
            }

            $('#frequency > thead').html('<tr></tr>');
            $('#frequency > tbody').html('');
            $('#buttons-frequency').html('');

            var is_first_to_third_year = data['is_first_to_third_year'];
            if(is_first_to_third_year == '1'){

                var special_days = data['special_days'];
                var no_school = data['no_school'];

                saturdays = data['saturday_school'];
                $('#widget-frequency').show();

                adicionaNivelFundamental(special_days, no_school);

                // index = 0;
                // classdays = '';
                // var month = $('#month').val();
                //
                // var year = new Date().getFullYear();
                //
                // var maxDays = new Date(year, month, 0).getDate();
                //
                //
                // var thead = '';
                // var tbody_td = '';

                // thead += "<th><span>Professor</span></th>"
                //
                // for (var day = 1; day <= maxDays; day++){
                //     var date = new Date(month+" "+day+" "+year);
                //
                //     var weekDay = date.getDay();
                //
                //     if (weekDay != 0){
                //         if(weekDay == 6 && checkSaturdaySchool(date, saturdays)){
                //             thead += '<th><span>';
                //             thead += day;
                //             thead += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             thead += '</span></th>';
                //
                //             tbody_td += '<td><span>';
                //             tbody_td += day;
                //             tbody_td += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             tbody_td += '</span></td>';
                //
                //             classdays += '<td><span>';
                //             classdays += day;
                //             classdays += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             classdays += '</span></td>';
                //         }
                //         if(weekDay < 6 && checkSpecialDay(date, special_days)){
                //
                //             thead += '<th><span>';
                //             thead += day;
                //             thead += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             thead += '</span></th>';
                //
                //             tbody_td += '<td><span>';
                //             tbody_td += day;
                //             tbody_td += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             tbody_td += '</span></td>';
                //
                //             classdays += '<td><span>';
                //             classdays += day;
                //             classdays += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                //             classdays += '</span></td>';
                //         }
                //     }
                //     thead += '</th>';
                // }
                // $('#frequency > thead > tr').append(thead);


                // console.log(classdays);
                // var tbody = "";
                // var students = data['students'];
                // students_array = data['students'];
                // console.log(students.length);
                //
                //
                // var name = students[0]['name'];
                // console.log(name);
                // tbody += '<tr>';
                // tbody += "<td><span>" + name + "</span></td>";
                // tbody += tbody_td;
                // tbody += '</tr>';
                // $('#frequency > tbody').append(tbody);
                //
                //
                //
                // var buttons_div = "<div id='buttons-frequency'></div>";
                // $(buttons_div).insertAfter('#widget-frequency');
                // if (index < students_array.length) {
                //     var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
                //     $('#buttons-frequency').append(next_student_button);
                // }
                // $('#next-student-button').click(function() {
                //     addStudentForward();
                // });
            }else{
                index = 0;
                classdays = '';
                var month = $('#month').val();

                var year = new Date().getFullYear();

                var maxDays = new Date(year, month, 0).getDate();

                var weekly_schedule =  data['weekly_schedule'];
                var special_days = data['special_days'];

                var thead = '';
                var tbody_td = '';
                $('#widget-frequency').show();
                thead += "<th><span>Professor</span></th>"
                for (var day = 1; day <= maxDays; day++){
                    var date = new Date(month+" "+day+" "+year);

                    var weekDay = date.getDay();


                    if(isset(weekly_schedule[weekDay]) && checkSpecialDay(date, special_days)){

                        thead += '<th><span>';
                        thead += day;
                        thead += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                        thead += '</span></th>';

                        tbody_td += '<td><span>';
                        tbody_td += day;
                        tbody_td += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                        tbody_td += '</span></td>';

                        classdays += '<td><span>';
                        classdays += day;
                        classdays += '<input id="day['+day+']" name="day['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(1 == 1 ? ' ' : ' checked ')+'>';
                        classdays += '</span></td>';
                    }
                    thead += '</th>';
                }
                $('#frequency > thead > tr').append(thead);
                console.log(classdays);
                var tbody = "";
                var students = data['students'];
                students_array = data['students'];
                console.log(students.length);


                var name = students[0]['name'];
                console.log(name);
                tbody += '<tr>';
                tbody += "<td><span>" + name + "</span></td>";
                tbody += tbody_td;
                tbody += '</tr>';
                $('#frequency > tbody').append(tbody);
                var buttons_div = "<div id='buttons-frequency'></div>";
                $(buttons_div).insertAfter('#widget-frequency');
                if (index < students_array.length) {
                    var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
                    $('#buttons-frequency').append(next_student_button);
                }
                $('#next-student-button').click(function() {
                    addStudentForward();
                });
            }
        }});
});
