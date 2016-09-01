var student0_name = "Fulano";
var student1_name = "Gabe Newell";
var students_array = new Array();
var index = 0;
students_array[0] = student0_name;
students_array[1] = student1_name;

$('#month, #disciplines, #classroom').on('change', function(){
    $('#frequency').hide();
})
$('#classroom').on('change', function(){
    $('#disciplines').val('').trigger('change');
});
$('#classesSearch').on('click', function(){
    jQuery.ajax({
        'type':'POST',
        'url':getClassesURL,
        'cache':false,
        'data':jQuery('#classroom').parents("form").serialize(),
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
            $('#frequency > thead').html('<tr><th class="center"></th></tr>');
            $('#frequency > tbody').html('');

            var month = $('#month').val();
            var year = new Date().getFullYear();

            var maxDays = new Date(year, month, 0).getDate();

            for(var day=1; day <= maxDays; day++){
                //MM DD YYYY
                var date = new Date(month+" "+day+" "+year);
                var weekDay = date.getDay();
                if(data['days'][weekDay][0] != "0" ) {

                    var thead = '<th class="center">' + day + '<br>';
                    /*$(data['days'][weekDay]).each(function(i, e){
                     var given = data['instructorFaults'] == undefined || data['instructorFaults'][day] == undefined || data['instructorFaults'][day][e-1] == undefined;

                     if(data['days'][weekDay][i] != "" ){
                     thead += '<span>';
                     thead += '<input id="day['+day+']['+e+']" name="day['+day+']['+e+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(given ? ' ' : ' checked ')+'>';
                     thead += '</span>';
                     }
                     });*/
                    thead += '</th>';
                    $('#frequency > thead > tr').append(thead);
                }
            }

            /* -------------------------- SOLUÇÃO GENÉRICA PARA A TASK DE UM ALUNO POR VEZ ------------------------- */
            /* ----------------------------------------------------------------------------------------------------- */

            var j = 0;
            var name = data['students']['name'][0];
            var tbody = "<tr>";
            tbody += '<td class="frequency-list" style="text-align: center;">'+ "Faltou?" +'</td>';
            for(var day=1; day <= maxDays; day++){

                var date = new Date(month+" "+day+" "+year);
                var weekDay = date.getDay();

                if(data['days'][weekDay][0] != "0" ){
                    tbody += '<td class="center">';
                    $(data['days'][weekDay]).each(function(i, e){
                        var fault = data['faults'] && data['faults'][day] != undefined && data['faults'][day][e] != undefined;
                        if (fault){
                            fault = false;
                            $(data['faults'][day][e]).each(function(shc, stId){
                                fault = fault || (data['students']['id'][j] == stId);
                            });
                        }
                        if(data['days'][weekDay][i] != "" ){
                            tbody += '<span>';
                            tbody += '<input id="day[' + day + '][' + e + ']" name="student[' + data['students']['id'][j] + '][' + day + '][' + e + ']" class="student-fault checkbox" type="checkbox" value="1"  last='+(fault ? '"false"' : '"true"')+'  style="opacity: 100;"' + (fault ? ' checked disabled' : ' ') + '>';
                            tbody += '</span>';
                        }
                    });
                    tbody += '</td>';
                }
            }
            tbody += "</tr>";
            $('#frequency > tbody').append(tbody);
            $('input.instructor-fault:checked').each(function(i, e) {
                var id = $(this).attr('id');
                var students = $("input.student-fault[id='" + id + "']");
                students.attr('disabled', 'disabled');
            });
            $("#frequency-student-name").text(students_array[0]);
            $('#widget-frequency').show();
            $('#frequency').show();
            $('#month_text').html($('#month').find('option:selected').text());
            $('#discipline_text').html($('#disciplines').find('option:selected').text());
            var buttons_div = "<div id='buttons-frequency'></div>";
            $(buttons_div).insertAfter('#widget-frequency');
            if (index < students_array.length) {
                var next_student_button = "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
                $('#buttons-frequency').append(next_student_button);
            }
            if (index > 0) {
                var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Próximo aluno<i></i></a>";
                $('#buttons-frequency').append(previous_student_button);
            }
        }});
});

$(document).on('click', '.instructor-fault', function() {
    var id = $(this).attr('id');
    var students = $("input.student-fault[id='" + id + "']");
    $(students).each(function(i, e) {
        var student = $(e);
        if ((student.attr('last') == 'true') && (student.attr('disabled') == 'disabled')) {
            student.removeAttr('disabled');
        } else {
            student.attr('disabled', 'disabled');
        }
    });
});

$('#next-student-button').click(function() {
    alert("huheuheuheu");
});


$(document).ready(function() {
    $('#frequency').hide();
});

$("#print").on('click', function() {
    window.print();
});

$("#save").on('click', function() {
    $("#classes-form").submit();
});

$('.heading-buttons').css('width', $('#content').width());