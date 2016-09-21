var students_array = new Array();
var index = 0;
var classdays = '';

function checkSpecialDay(date, specialdays){
    $(specialdays).each(function (i, period) {
        var start_date = new Date(period['start_date']);
        var end_date = new Date(period['end_date']);
       if(start_date.getTime() <= date.getTime() && end_date.getTime() >= date.getTime()){
           return false;
       }
    });
    return true;
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

    console.log(index);
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
        console.log(index);
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>").insertAfter('#previous-student-button')
        $('#next-student-button').click(function() {
            addStudentForward();
        });
    }
    index--;
    console.log(index);
    if (index == 0) {
        $('#previous-student-button').remove();
    }
    console.log(students_array.length - 1);


    $('#frequency > tbody').html('');
    var tbody = "";
    tbody += '<tr>';
    tbody += "<td><span>" + students_array[index]['name'] + "</span></td>"
    tbody += classdays
    tbody += '</tr>';
    $('#frequency > tbody').append(tbody);
}


$('#classesSearch').on('click', function(){
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
                index = 0;
                classdays = '';
                var month = $('#month').val();

                var year = new Date().getFullYear();

                var maxDays = new Date(year, month, 0).getDate();

                var weekly_schedule =  data['weekly_schedule'];
                var special_days = data['special_days'];
                var saturdays = data['saturday_school'];

                var thead = '';
                var tbody_td = '';
                $('#widget-frequency').show();
                thead += "<th><span>Professor</span></th>"

                for (var day = 1; day <= maxDays; day++){
                    var date = new Date(month+" "+day+" "+year);

                    var weekDay = date.getDay();

                    if (weekDay != 0){
                        if(weekDay == 6 && checkSaturdaySchool(date, saturdays)){
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
                        if(weekDay < 6 && checkSpecialDay(date, special_days)){

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
