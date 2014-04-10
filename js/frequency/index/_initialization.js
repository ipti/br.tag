   $('#month, #disciplines, #classroom').on('change', function(){
        $('#frequency').hide();
    })
    $('#classroom').on('change', function(){
        $('#disciplines').val('').trigger('change');
    });
    $('#classesSearch').on('click', function(){
        jQuery.ajax({
            'type':'POST',
            'url':baseUrl+'/index.php?r=frequency/getClasses',
            'cache':false,
            'data':jQuery('#classroom').parents("form").serialize(),
            'success':function(data){
                var data = jQuery.parseJSON(data);
                console.log(data['days'] == undefined);
                if(data['days'] == undefined) {
                    $('#frequency > thead').html('<tr><th class="center">Não há aulas desta matéria.</th></tr>');
                    $('#frequency > tbody').html('');
                    $('#widget-frequency').show();
                    $('#frequency').show();
                    return true;
                }
                console.log(data['days']);
                $('#frequency > thead').html('<tr><th class="center">Alunos</th></tr>');
                $('#frequency > tbody').html('');
                
                var month = $('#month').val();
                var year = new Date().getFullYear();
                
                var maxDays = new Date(year, month, 0).getDate();
                
                for(var day=1; day <= maxDays; day++){
                    //MM DD YYYY
                    var date = new Date(month+" "+day+" "+year);
                    var weekDay = date.getDay();
                    if(data['days'][weekDay][0] != "0" ){
                        var thead = '<th class="center">'+day+'<br>';
                        $(data['days'][weekDay]).each(function(i, e){
                             var given = data['instructorFaults'] == undefined || data['instructorFaults'][day] == undefined || data['instructorFaults'][day][e-1] == undefined;

                             if(data['days'][weekDay][i] != "" ){
                                thead += '<span>';
                                thead += '<input id="day['+day+']['+e+']" name="day['+day+']['+e+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(given ? ' ' : ' checked ')+'>';
                                thead += '</span>';
                            }
                        });
                        thead += '</th>';
                        $('#frequency > thead > tr').append(thead);
                    }
                    
                }
                
                $(data['students']['name']).each(function(j, name){
                    var tbody = "<tr>";
                    tbody += '<td class="frequency-list">'+name+'</td>';
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
                                    tbody += '<input id="day[' + day + '][' + e + ']" name="student[' + data['students']['id'][j] + '][' + day + '][' + e + ']" class="student-fault checkbox" type="checkbox" value="1" style="opacity: 100;"' + (fault ? ' checked disabled' : ' ') + '>';
                                    tbody += '</span>';
                                }
                            });
                            tbody += '</td>';
                        }
                    }
                    tbody += "</tr>";
                    $('#frequency > tbody').append(tbody);
                });

                $('input.instructor-fault:checked').each(function(i, e) {
                    var id = $(this).attr('id');
                    var students = $("input.student-fault[id='" + id + "']");
                    students.attr('disabled', 'disabled');

                })

                $('#widget-frequency').show();
                $('#frequency').show();
                $('#month_text').html($('#month').find('option:selected').text());
                $('#discipline_text').html($('#disciplines').find('option:selected').text());
            }});
    });

    $(document).on('click', '.instructor-fault', function() {
        var id = $(this).attr('id');
        var students = $("input.student-fault[id='" + id + "']");
        $(students).each(function(i, e) {
            var student = $(e);
            if (student.attr('disabled') == 'disabled' && !student.attr('checked')) {
                student.removeAttr('disabled');
            } else {
                student.attr('disabled', 'disabled');
            }
        });
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