////////////////////////////////////////////////
// Functions                                  //
////////////////////////////////////////////////
var removeTeachingData = function(){
    var instructor = $(this).parent().parent().parent().attr("instructor");
    var discipline = ($(this).parent().attr("discipline"));
    if (instructor == undefined){
        instructor = $(this).parent().attr("instructor");
        if (instructor == undefined){
            disciplines[discipline] = 0;
            $("#DisciplinesWithoutInstructors li[discipline = "+discipline+"]").remove();
        }else{
            removeInstructor(instructor);
        }
    }
    else{
        removeDiscipline(instructor,discipline);
    }
}

var removeInstructor = function(instructor){
    for(var i = 0; i < teachingData.length; i++){
        if(teachingData[i].Instructor == instructor){
            for(var j = 0; j < teachingData[i].Disciplines.length;j++){
                removeDiscipline(instructor,teachingData[i].Disciplines[j])
            }
            teachingData.splice(i, 1);
        }
    }
    $("li[instructor = "+instructor+"]").remove();
}

var removeDiscipline = function(instructor, discipline){
    var count = 0;
    for(var i = 0; i < teachingData.length; i++){
        for(var j = 0; j < teachingData[i].Disciplines.length;j++){
            if(discipline == teachingData[i].Disciplines[j])
                count++;
        }
    }

    for(var i = teachingData.length; i--;) {
        if(teachingData[i].Instructor == instructor) {
            for(var j = teachingData[i].Disciplines.length; j--;){
                if(teachingData[i].Disciplines[j] == discipline)
                    teachingData[i].Disciplines.splice(j, 1);
            }
            if(teachingData[i].Disciplines.length == 0){
                teachingData.splice(i, 1);
                $("li[instructor = "+instructor+"]").remove();
            }
            if(count <= 1){
                disciplines[discipline] = 0;
            }
        }
    }
    $("li[instructor = "+instructor+"] li[discipline = "+discipline+"]").remove();
}

var addTeachingData = function(){
    var instructorName = $('#s2id_Instructors span').text();
    var instructorId = $('#Instructors').val();

    var disciplineList = $("#Disciplines").val(); 
    var disciplineNameList = [];

    var role = $("#Role").val(); 
    var contract = $("#ContractType").val(); 

    $.each($("#s2id_Disciplines li.select2-search-choice"), function(i,v){
        disciplineNameList[i] = $(v).text();
    });

    //Se for uma string vazia
    if(instructorId.length == 0){
        $.each(disciplineNameList, function(i,name){
            if($("#DisciplinesWithoutInstructors li[discipline="+disciplineList[i]+"]").length == 0){
                $("#DisciplinesWithoutInstructors").append(""
                    +"<li discipline='"+disciplineList[i]+"'><span>"+name+"</span>"
                    +"<a href='#' class='deleteTeachingData delete' title='Excluir'> </a> "
                    +"</li>");
            }
            disciplines[disciplineList[i]] = 2;
        });
    }else{
        var td = {
            Instructor : instructorId,
            Classroom : null,
            Role : role,
            ContractType : contract,
            Disciplines : []
        };
        var html = "";
        var tag = "";

        var hasInstructor = $("li[instructor = "+instructorId+"]").length != 0;
        var instructorIndex = -1;

        if (!hasInstructor){
            tag = "#DisciplinesWithInstructors";
            html = "<li instructor='"+instructorId+"'><span>"+instructorName+"</span>"
                +"<a href='#' class='deleteTeachingData delete' title='Excluir'> </a>"
                +"<ul>";
        }else{
            $.each(teachingData, function(i, data){
                if(data.Instructor == instructorId)
                    instructorIndex = i;
            });
            tag = "#DisciplinesWithInstructors li[instructor = "+instructorId+"] ul";
        }

        $.each(disciplineNameList, function(i,name){
            var hasDiscipline = $("li[instructor = "+instructorId+"] li[discipline="+disciplineList[i]+"]").length != 0;
            if(!hasDiscipline){
                html += "<li discipline='"+disciplineList[i]+"'>"+name
                    +"<a href='#' class='deleteTeachingData delete' title='Excluir'></a>"
                    +"</li>";
                if(!hasInstructor)
                    td.Disciplines.push(disciplineList[i]);
                else
                    teachingData[instructorIndex].Disciplines.push(disciplineList[i]);
            }
            disciplines[disciplineList[i]] = 1;
        });

        if (!hasInstructor){
            html += "</ul>"
                +"</li>";
            teachingData.push(td);
        }
        $(tag).append(html);
    }
}

//Cria estrutura de uma aula
//Retorna um array
//O Ajax da problema de recursividade se colocado aqui
var createNewLesson = function() {
    lesson = {
        id: lesson_id++,
        id_db: 0,
        title: (discipline.find('option:selected').text().length > 30) ? discipline.find('option:selected').text().substring(0,27) + "..." : discipline.find('option:selected').text() + ' - ' + (instructor.val() == "" ? "Sem Professor" : teachingDataNames[instructor.val()]),
        discipline: discipline.val(),
        start: lesson_start,
        end: lesson_end,
        classroom: classroomId,
        instructor: instructor.val(),
    };
    return lesson;
}

//Atualiza estrutura de uma aula
//Retorna um array
//O Ajax da problema de recursividade se colocado aqui
var updateLesson = function(l) {
    lesson = {
        id: l.id,
        db: l.db,
        title: l.title + ' - ' + teachingDataNames[l.instructor],
        discipline: uDiscipline.val(),
        start: l.start,
        end: l.end,
        instructor: uInstructor.val(),
        classroom: l.classroom,
    };
    return lesson;
}

//var instructor = $("#insertclass-instructor");
//var uInstructor = $("#insertclass-update-instructor");
//
//atualizar lista de instrutores
function atualizaListadeInstrutores(){
    var listOfinstructors = '<option value="">Selecione o instrutor</option>';

    $.each(teachingData,function(i,td){
        listOfinstructors += '<option value="'+td.Instructor+'">'+teachingDataNames[td.Instructor]+'</option>';
    });
    instructor.html(listOfinstructors).trigger('change');
    uInstructor.html(listOfinstructors).trigger('change');
};

var atualizarListadeDisciplinas = function(){
    //atualizar lista de disciplinas
    var self = this;
    var listOfdisciplines = '<option value="">Selecione a disciplina</option>';
    if($(self).val() == ''){
        $.each(disciplines,function(i,d){
            if (d == 2)
            listOfdisciplines += '<option value="'+i+'">'+disciplinesLabels[i]+'</option>';
        });
    }else{
        $.each(teachingData,function(i,td){
            if (td.Instructor == $(self).val()){
                $.each(td.Disciplines, function(j,d){
                    listOfdisciplines += '<option value="'+d+'">'+disciplinesLabels[d]+'</option>';
                });
            }
        });
    }
    discipline.html(listOfdisciplines).trigger('change');
    uDiscipline.html(listOfdisciplines).trigger('change');
};


/**
 * Atualiza as dependencias do tipo de atendimento.
 * 
 * @param {JSON} data
 * @returns {void}
 */
function updateAssistanceTypeDependencies(data){     
    data = jQuery.parseJSON(data);
    
    var type = $('#Classroom_assistance_type').val();
    
    //+edu
    if(type == 1 || type == 5){
        $('#mais_educacao #none input').val(null).removeAttr('disabled');
        $('#mais_educacao #some input').attr('disabled', 'disabled');
        $('#mais_educacao').hide();
    }else{
        $('#mais_educacao #none input').val(null).attr('disabled', 'disabled');
        $('#mais_educacao #some input').removeAttr('disabled', 'disabled');
        $('#mais_educacao').show();
    }
    
    if(type == 4){
        $("#complementary_activity input").val(null).removeAttr('disabled');
        $("#complementary_activity").show();
    }else{
        $("#complementary_activity input").val(null).attr('disabled', 'disabled');
        $("#complementary_activity").hide();
    }
    
    //aee
    if(type == 5){
        $('#aee input').removeAttr('disabled');
        $("#aee").show();
    }else{
        $('#aee input').attr('disabled', 'disabled');
        $("#aee").hide();
    }
    
    //M & SvsM
    if(type == 4 || type == 5){
        $("#modality input").val(null).attr('disabled', 'disabled').trigger('change');
        $("#stage_vs_modality input").val(null).attr('disabled','disabled').trigger('change');
        $("#modality").hide();
        $("#stage_vs_modality").hide();
    }else{
        $('#Classroom_modality').html(data.Modality).removeAttr('disabled').trigger('change');  
        $("#stage_vs_modality input").html(data.Stage).removeAttr('disabled').trigger('change');
        $("#modality").show();
        $("#stage_vs_modality").show();
    }
}

/**
 * Modifica o Horário de entrada e de saída a partir dos dados recebidos pelo JSON
 * 
 * @param {JSON} data
 * @returns {void}
 */
function updateTime(data){
    data = jQuery.parseJSON(data);
	$("#Classroom_initial_time").val(data.first !== null ? data.first.substring(0, 5) : $("#Classroom_initial_time").val());
	$("#Classroom_final_time").val(data.last !== null ? data.last.substring(0, 5) : $("#Classroom_final_time").val());
}


instructor.on('change', atualizarListadeDisciplinas);
uInstructor.on('change', atualizarListadeDisciplinas);

$(document).on('click','.deleteTeachingData',removeTeachingData);
$("#addTeachingData").on('click', addTeachingData);

$(document).on("change", ".assistance-types-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "Classroom_aee") {
        $("#Classroom_aee").prop("checked", false);
    } else {
        $(".assistance-types-container input[type=checkbox]").not("#Classroom_aee").prop("checked", false);
    }
});