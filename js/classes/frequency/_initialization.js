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


function  adicionaProfessorPrimario(special_days, no_school, saturdays) {

    var thead = '';
    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    thead += "<th><span'>Professor</span></th>";
    for (var day = 1; day <= maxDays; day++){
        var date = new Date(month+" "+day+" "+year);

        var weekDay = date.getDay();
        if (weekDay != 0){
            var disabled = '';
            var checked = '';
            var className = 'class = "checkcolor"';
            var value = 1;

            if ((checkSpecialDay(date, special_days))  ) {
                disabled = ' readonly onclick="return false" ';
                checked = " checked ";
                className = 'style = "color:red"'
                value = 2;
            }
            if (checkNoSchool(day, no_school)) {
                checked = ' checked ';
            }
            if ((weekDay < 6) || (weekDay == 6 && checkSaturdaySchool(date, saturdays))){
                var dayForClass = day;
                if (parseInt(dayForClass) < 10) {
                    dayForClass = '0' + dayForClass;
                }
                thead += '<th><span '+className+'>';
                thead += '<input type="hidden" name=instructor_days['+day+'] value="'+day+'">';
                thead += day;
                thead += '<input id="instructor_faults['+dayForClass+']" name="instructor_faults['+day+']" class="instructor-fault checkbox no-show" type="checkbox" value="'+value+'" style="opacity: 100;"'+checked+disabled+'>';
                thead += '</span></th>';
            }
        }

    }
    return thead;
}

function adicionaProfessorSecundario(schedule,special_days, no_school, saturdays){
    var thead = '';
    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    thead += "<th><span>Professor</span></th>";
    for (var day = 1; day <= maxDays; day++){
        var date = new Date(month+" "+day+" "+year);

        var weekDay = date.getDay();
        if (isset(schedule[weekDay])){
            var disabled = '';
            var checked = '';
            var value = 1

            if ((checkSpecialDay(date, special_days))  ) {
                disabled =  ' readonly onclick="return false"';
                checked = " checked ";
                value = 2;
            }
            if (checkNoSchool(day, no_school)) {
                checked = ' checked ';
            }
            if ((weekDay < 6) || (weekDay == 6 && checkSaturdaySchool(date, saturdays))){
                var dayForClass = day;
                if (parseInt(dayForClass) < 10) {
                    dayForClass = '0' + dayForClass;
                }
                thead += '<th><span class="checkcolor">';
                thead += '<input type="hidden" name=instructor_days['+day+'] value="'+day+'">';
                thead += day;
                thead += '<input id="instructor_faults['+dayForClass+']" name="instructor_faults['+day+']" class="instructor-fault checkbox no-show" type="checkbox" value="'+value+'" style="opacity: 100;"'+checked+disabled+'>';
                thead += '</span></th>';
            }
        }

    }
    return thead;
}

function adicionaEstudantePrimario(students, special_days, no_school, saturdays){

    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    var tbody = "";
    for(var index = 0; index < students.length; index++){

        var name = students[index]['name'];

        var faults = students[index]['faults'];
        var id = students[index]['id'];
        tbody += '<tr id="'+index+'" style="display: none">';
        tbody += "<td><span>" + name + "</span></td>";

        for (var day = 1; day <= maxDays; day++) {
            var date = new Date(month + " " + day + " " + year);

            var weekDay = date.getDay();

            if (weekDay != 0) {

                var disabled = '';
                var checked = '';
                var value = 1
                if ((checkSpecialDay(date, special_days))  ) {
                    checked = ' checked ';
                    disabled = ' readonly onclick="return false"';
                    value = 2
                }
                if (checkNoSchool(day, no_school)) {
                    checked = ' checked ';
                    disabled = ' readonly onclick="return false"'

                }
                if (checkFault(day, faults)) {
                    checked = ' checked ';
                }

                if ((weekDay < 6) || (weekDay == 6 && checkSaturdaySchool(date, saturdays))){
                    var dayForClass = day;
                    if (parseInt(dayForClass) < 10) {
                        dayForClass =  '0' + dayForClass;
                    }
                    tbody += '<td><span class="checkcolor">';
                    tbody += day;
                    tbody += '<input id="student_faults['+id+']['+day+']" name="student_faults['+id+']['+day+']" class="instructor-fault no-show checkbox student_check'+dayForClass+'" type="checkbox" value="'+value+'" style="opacity: 100;"'+checked+disabled+'>';
                    tbody += '</span></td>';
                }

            }
        }
        tbody += '</tr>';
    }



    return tbody;
}

function adicionaEstudanteSecundario(schedule, students, special_days, no_school, saturdays) {

    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();

    var tbody = "";
    for(var index = 0; index < students.length; index++){

        var name = students[index]['name'];

        var faults = students[index]['faults'];
        var id = students[index]['id'];
        tbody += '<tr id="'+index+'" style="display: none">';
        tbody += "<td><span>" + name + "</span></td>";

        for (var day = 1; day <= maxDays; day++) {
            var date = new Date(month + " " + day + " " + year);

            var weekDay = date.getDay();

            if (isset(schedule[weekDay])){

                var disabled = '';
                var checked = '';
                var value = 1
                if ((checkSpecialDay(date, special_days))  ) {
                    checked = ' checked ';
                    disabled = 'readonly onclick="return false"';
                    value = 2
                }
                if (checkNoSchool(day, no_school)) {
                    checked = ' checked ';
                    disabled = 'readonly onclick="return false"'

                }
                if (checkFault(day, faults)) {
                    checked = ' checked ';
                }

                if ((weekDay < 6) || (weekDay == 6 && checkSaturdaySchool(date, saturdays))){
                    var dayForClass = day;
                    if (parseInt(dayForClass) < 10) {
                        dayForClass = '0' + dayForClass;
                    }
                    tbody += '<td><span class="checkcolor">';
                    tbody += day;
                    tbody += '<input id="student_faults['+id+']['+day+']" name="student_faults['+id+']['+day+']" class="instructor-fault checkbox  no-show student_check'+dayForClass+'" " type="checkbox" value="+value+" style="opacity: 100;"'+checked+disabled+'>';
                    tbody += '</span></td>';
                }

            }
        }
        tbody += '</tr>';
    }

    return tbody;
}

function adicionaHorarios(schedule, special_days, no_school, saturdays, students, is_first_to_third_year) {

    if(is_first_to_third_year == '1'){

        var thead = adicionaProfessorPrimario(special_days, no_school);
        var tbody = adicionaEstudantePrimario(students, special_days, no_school, saturdays);

        $('#frequency > thead > tr').append(thead);
        $('#frequency > tbody').append(tbody);

        $('#0').show();

        var buttons_div = "<div id='buttons-frequency'></div>";
        $(buttons_div).insertAfter('#widget-frequency');
        if (index_count < students.length) {
            var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
            $('#buttons-frequency').append(next_student_button);
        }
        $('#next-student-button').click(function() {
            addStudentForward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    } else {
        var thead = adicionaProfessorSecundario(schedule, special_days, no_school);
        var tbody = adicionaEstudanteSecundario(schedule, students, special_days, no_school, saturdays);

        $('#frequency > thead > tr').append(thead);
        $('#frequency > tbody').append(tbody);

        $('#0').show();

        var buttons_div = "<div id='buttons-frequency'></div>";
        $(buttons_div).insertAfter('#widget-frequency');
        if (index_count < students.length) {
            var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
            $('#buttons-frequency').append(next_student_button);
        }
        $('#next-student-button').click(function() {
            addStudentForward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }
    $(".checkcolor").each(function () {
        if ($(this).find(".checkbox").prop("checked")){
            $(this).css('color', 'red');

        } else {
            $(this).css('color', 'black');
        }
    })

    $(".checkcolor").click(function(){

        var checked = $(this).find(".checkbox")[0];
        if ($(this).find(".checkbox").prop("checked")){
            $(this).css('color', 'black');
            $(this).find(".checkbox").prop("checked", false);
            if(checked.id.match(/^instructor_faults.*$/)){
                $(".student_check" + checked.id.substr(18,2)).parent().css('color', 'black');
                $(".student_check" + checked.id.substr(18,2)).prop('checked', false);
                $(".student_check" + checked.id.substr(18,2)).prop('readonly', false);}

        } else {
            $(this).css('color', 'red');
            $(this).find(".checkbox").prop("checked", true);
            if(checked.id.match(/^instructor_faults.*$/)){
                $(".student_check" + checked.id.substr(18,2)).parent().css('color', 'red');
                $(".student_check" + checked.id.substr(18,2)).prop('checked', true);
                $(".student_check" + checked.id.substr(18,2)).prop('readonly', true);}

        }
    });

}

function addStudentForward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year) {
    $('#'+index_count).hide();
    index_count++;
    $('#'+index_count).show();
    if (index_count == students.length - 1) {

        $('#next-student-button').remove();
    }
    if (index_count == 1 && index_count <= students.length) {
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>").insertBefore('#next-student-button')
        $('#previous-student-button').click(function() {
            addStudentBackward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }
    $('#frequency > tbody > tr > td:first-child').width('30em');
}

function  addStudentBackward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year) {
    if (index_count == (students.length - 1) && index_count > 0) {

        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $("<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>").insertAfter('#previous-student-button')
        $('#next-student-button').click(function() {
            addStudentForward(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
        });
    }
    $('#'+index_count).hide();
    index_count--;
    $('#'+index_count).show();

    if (index_count == 0) {
        $('#previous-student-button').remove();
    }
    $('#frequency > tbody > tr > td:first-child').width('30em');
}


$('#classesSearch').on('click', function(){
    jQuery.ajax({
        'type':'GET',
        'url':getClassesURL,
        'cache':false,
        'data':{classroom: $("#classroom").val(), month: $("#month").val(),disciplines: $("#disciplines").val()},
        'success':function(data){
            var data = jQuery.parseJSON(data);
            console.log(data);
            if(data['days'] == undefined) {
                $('#frequency > thead').html('<tr><th class="center">Não há aulas desta matéria.</th></tr>');
                $('#frequency > tbody').html('');
                $('#widget-frequency').show();
                $('#frequency').show();
                return true;
            }

            $('#frequency > thead').html('<tr></tr>');
            $('#frequency > tbody').html('');
            $('#frequency > tbody').attr('id', 'frequency-body');
            $('#frequency > tbody > tr > td:first-child').width('30em');

            $('#buttons-frequency').html('');
            index_count = 0;


            $("#save").on('click', function () {
                $("#classes-form").submit();
            });

            var special_days = data['special_days'];
            var no_school = data['no_school'];
            var students = data['students'];
            var schedule = data['weekly_schedule'];
            var saturdays = data['saturday_school'];


            var is_first_to_third_year = data['is_first_to_third_year'];
            if(is_first_to_third_year == '1'){

                $('#widget-frequency').show();

                adicionaHorarios(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
            }else{
                $('#widget-frequency').show();

                adicionaHorarios(schedule, special_days, no_school, saturdays, students, is_first_to_third_year);
            }
        }}); 
});
