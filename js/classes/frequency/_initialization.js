var students_array = new Array();
var index_count = 0;
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
    var count = 0;
    $(saturdays).each(function (i, period) {
        var start_date = new Date(period['start_date']);
        var end_date = new Date(period['end_date']);
        if(start_date.getTime() <= date.getTime() && end_date.getTime() >= date.getTime()){
            count++;
        }
    });
    return  count > 0;
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


function checkFault(day, faults){
    var count = 0;
    $(faults).each(function (i, days) {
        var fault = days['day'];
        if(day == fault){
            count++;
        }
    });
    return count > 0;
}


function  adicionaProfessorFundamental(special_days, no_school, saturdays) {

    var thead = '';
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
                thead += '<input id="instructor_faults['+day+']" name="instructor_faults['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;">';
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
                thead += '<input id="instructor_faults['+day+']" name="instructor_faults['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+checked+disabled+'>';
                thead += '</span></th>';
            }
        }

    }
    return thead;

}

function adicionaEstudanteFundamental(students, special_days, no_school, saturdays){


    console.log(index_count);

    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();

    var tbody = "";
    console.log(students[0]);
    var name = students[index_count]['name'];
    console.log(name);
    var faults = students[index_count]['faults'];
    var id = students[index_count]['id'];
    tbody += '<tr>';
    tbody += "<td><span>" + name + "</span></td>";

    for (var day = 1; day <= maxDays; day++) {
        var date = new Date(month + " " + day + " " + year);

        var weekDay = date.getDay();

        if (weekDay != 0) {
            if(weekDay == 6 && checkSaturdaySchool(date, saturdays)){
                tbody += '<td><span>';
                tbody += day;
                tbody += '<input id="student_faults['+id+']['+day+']" name="student_faults['+id+']['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;">';
                tbody += '</span></td>';
            }
            if(weekDay < 6){
                var disabled = '';
                var checked = '';
                if ((checkSpecialDay(date, special_days))  ) {
                    checked = ' checked ';
                    disabled = ' disabled ';
                }
                if (checkNoSchool(day, no_school)) {
                    checked = ' checked ';
                }
                if (checkFault(day, faults)) {
                    checked = ' checked ';
                }
                tbody += '<td><span>';
                tbody += day;
                tbody += '<input id="student_faults['+id+']['+day+']" name="student_faults['+id+']['+day+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+checked+disabled+'>';
                tbody += '</span></td>';
            }
        }
    }
    tbody += '</tr>';

    return tbody;
}

function adicionaNivelFundamental(special_days, no_school, saturdays, students, is_first_to_third_year) {

    if(is_first_to_third_year == '1'){

        var thead = adicionaProfessorFundamental(special_days, no_school);
        var tbody = adicionaEstudanteFundamental(students, special_days, no_school, saturdays);

        $('#frequency > thead > tr').append(thead);
        $('#frequency > tbody').append(tbody);

        var buttons_div = "<div id='buttons-frequency'></div>";
        $(buttons_div).insertAfter('#widget-frequency');
        if (index_count < students.length) {
            var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
            $('#buttons-frequency').append(next_student_button);
        }
        $('#next-student-button').click(function() {
            addStudentForward(special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }



}


function addStudentForward(special_days, no_school, saturdays, students, is_first_to_third_year) {
    index_count++;
    if (index_count == students.length - 1) {

        $('#next-student-button').remove();
    }

    $('#frequency > tbody').html('');
    var tbody = '';
    if(is_first_to_third_year == '1'){
        tbody += adicionaEstudanteFundamental(students, special_days, no_school, saturdays);
    }
    $('#frequency > tbody').append(tbody);

    if (index_count == 1 && index_count <= students.length) {
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>").insertBefore('#next-student-button')
        $('#previous-student-button').click(function() {
            addStudentBackward(special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }
}

function  addStudentBackward(special_days, no_school, saturdays, students, is_first_to_third_year) {
    if (index_count == (students.length - 1) && index_count > 0) {

        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>").insertAfter('#previous-student-button')
        $('#next-student-button').click(function() {
            addStudentForward(special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }
    index_count--;

    if (index_count == 0) {
        $('#previous-student-button').remove();
    }

    $('#frequency > tbody').html('');
    var tbody = '';
    if(is_first_to_third_year == '1'){
        tbody += adicionaEstudanteFundamental(students, special_days, no_school, saturdays);
    }
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
                var students = data['students'];

                saturdays = data['saturday_school'];
                $('#widget-frequency').show();

                adicionaNivelFundamental(special_days, no_school, saturdays, students, is_first_to_third_year);

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
                //index = 0;
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
