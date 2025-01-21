////////////////////////////////////////////////
// Functions                                  //
////////////////////////////////////////////////
var count = $('.regent-teacher').length;
var RegentTeacherCount = count;

var removeTeachingData = function () {
    var instructor = $(this).parent().parent().parent().attr("instructor");
    var discipline = ($(this).parent().attr("discipline"));
    var isRegent = $(this).attr("regent");
    if (instructor == undefined) {
        instructor = $(this).parent().attr("instructor");
        if (instructor == undefined) {
            disciplines[discipline] = 0;
            $("#DisciplinesWithoutInstructors li[discipline = " + discipline + "]").remove();
        } else {
            if (isRegent == 1) {
                RegentTeacherCount--;
            }
            removeInstructor(instructor);
        }
    } else {
        removeDiscipline(instructor, discipline);
    }
}

var removeInstructor = function (instructor) {
    for (var i = 0; i < teachingData.length; i++) {
        if (teachingData[i].Instructor == instructor) {
            for (var j = 0; j < teachingData[i].Disciplines.length; j++) {
                removeDiscipline(instructor, teachingData[i].Disciplines[j])
            }
            teachingData.splice(i, 1);
        }
    }
    $("li[instructor = " + instructor + "]").remove();
}

var removeDiscipline = function (instructor, discipline) {
    var count = 0;
    for (var i = 0; i < teachingData.length; i++) {
        for (var j = 0; j < teachingData[i].Disciplines.length; j++) {
            if (discipline == teachingData[i].Disciplines[j])
                count++;
        }
    }

    for (var i = teachingData.length; i--;) {
        if (teachingData[i].Instructor == instructor) {
            for (var j = teachingData[i].Disciplines.length; j--;) {
                if (teachingData[i].Disciplines[j] == discipline)
                    teachingData[i].Disciplines.splice(j, 1);
            }
            if (teachingData[i].Disciplines.length == 0) {
                teachingData.splice(i, 1);
                $("li[instructor = " + instructor + "]").remove();
            }
            if (count <= 1) {
                disciplines[discipline] = 0;
            }
        }
    }
    $("li[instructor = " + instructor + "] li[discipline = " + discipline + "]").remove();
}
$(document).on("change", "#Role", function () {
    $(".regent-teacher-container").hide()
    if ($(this).val() == 1 && RegentTeacherCount < 2) {
        $(".regent-teacher-container").show()
    }
})

var addTeachingData = function () {
    var instructorName = $('#s2id_Instructors span').text();
    var instructorId = $('#Instructors').val();

    var disciplineList = $("#Disciplines").val();
    var disciplineNameList = [];

    var role = $("#Role").val();
    var contract = $("#ContractType").val();
    var regent = $("#RegentTeacher").is(':checked') ? 1 : 0;

    $.each($("#s2id_Disciplines li.select2-search-choice"), function (i, v) {
        disciplineNameList[i] = $(v).text();
    });

    //Se for uma string vazia
    if (instructorId.length == 0) {
        $.each(disciplineNameList, function (i, name) {
            if ($("#DisciplinesWithoutInstructors li[discipline=" + disciplineList[i] + "]").length == 0) {
                $("#DisciplinesWithoutInstructors").append(""
                    + "<li class='li-discipline' discipline='" + disciplineList[i] + "'><span>" + name + "</span>"
                    + "<a href='#' class='deleteTeachingData delete' title='Excluir'> </a> "
                    + "</li>");
            }
            disciplines[disciplineList[i]] = 2;
        });
    } else {
        var td = {
            Instructor: instructorId,
            Classroom: null,
            Role: role,
            ContractType: contract,
            RegentTeacher: regent,
            Disciplines: []
        };
        var html = "";
        var tag = "";

        var hasInstructor = $("li[instructor = " + instructorId + "]").length != 0;
        var instructorIndex = -1;

        if (!hasInstructor) {
            regentLabel = ""
            if (regent) {
                regentLabel = " (Regente)"
                RegentTeacherCount++
            }
            tag = "#DisciplinesWithInstructors";
            html = "<li class='li-instructor' instructor='" + instructorId + "'><span>" + instructorName + "</span>" + "<span>" + regentLabel + "</span>"
                + "<a href='#' class='deleteTeachingData delete' title='Excluir' regent='" + regent + "'> </a>"
                + "<ul>";
        } else {
            $.each(teachingData, function (i, data) {
                if (data.Instructor == instructorId)
                    instructorIndex = i;
            });
            tag = "#DisciplinesWithInstructors li[instructor = " + instructorId + "] ul";
        }

        $.each(disciplineNameList, function (i, name) {
            var hasDiscipline = $("li[instructor = " + instructorId + "] li[discipline=" + disciplineList[i] + "]").length != 0;
            if (!hasDiscipline) {
                html += "<li class='li-discipline' discipline='" + disciplineList[i] + "'>" + name
                    + "<a href='#' class='deleteTeachingData delete' title='Excluir'></a>"
                    + "</li>";
                if (!hasInstructor)
                    td.Disciplines.push(disciplineList[i]);
                else
                    teachingData[instructorIndex].Disciplines.push(disciplineList[i]);
            }
            disciplines[disciplineList[i]] = 1;
        });

        if (!hasInstructor) {
            html += "</ul>"
                + "</li>";
            teachingData.push(td);
        }
        $(tag).append(html);
    }
    if (RegentTeacherCount == 2) {
        $(".regent-teacher-container").hide();
    }
    $('#RegentTeacher').prop('checked', false);
    console.log(RegentTeacherCount)
}

//Cria estrutura de uma aula
//Retorna um array
//O Ajax da problema de recursividade se colocado aqui
var createNewLesson = function () {
    lesson = {
        id: lesson_id++,
        id_db: 0,
        title: (discipline.find('option:selected').text().length > 30) ? discipline.find('option:selected').text().substring(0, 27) + "..." : discipline.find('option:selected').text() + ' - ' + (instructor.val() == "" ? "Sem Professor" : teachingDataNames[instructor.val()]),
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
var updateLesson = function (l) {
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
function atualizaListadeInstrutores() {
    var listOfinstructors = '<option value="">Selecione o instrutor</option>';

    $.each(teachingData, function (i, td) {
        listOfinstructors += '<option value="' + td.Instructor + '">' + teachingDataNames[td.Instructor] + '</option>';
    });
    instructor.html(listOfinstructors).trigger('change');
    uInstructor.html(listOfinstructors).trigger('change');
};

var atualizarListadeDisciplinas = function () {
    //atualizar lista de disciplinas
    var self = this;
    var listOfdisciplines = '<option value="">Selecione a disciplina</option>';
    if ($(self).val() == '') {
        $.each(disciplines, function (i, d) {
            if (d == 2)
                listOfdisciplines += '<option value="' + i + '">' + disciplinesLabels[i] + '</option>';
        });
    } else {
        $.each(teachingData, function (i, td) {
            if (td.Instructor == $(self).val()) {
                $.each(td.Disciplines, function (j, d) {
                    listOfdisciplines += '<option value="' + d + '">' + disciplinesLabels[d] + '</option>';
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
function updateAssistanceTypeDependencies(data) {
    data = jQuery.parseJSON(data);

    var type = $('#Classroom_assistance_type').val();

    //+edu
    if (type == 1 || type == 5) {
        $('#mais_educacao #none input').val(null).removeAttr('disabled');
        $('#mais_educacao #some input').attr('disabled', 'disabled');
        $('#mais_educacao').hide();
    } else {
        $('#mais_educacao #none input').val(null).attr('disabled', 'disabled');
        $('#mais_educacao #some input').removeAttr('disabled', 'disabled');
        $('#mais_educacao').show();
    }

    if (type == 4) {
        $("#complementary_activity input").val(null).removeAttr('disabled');
        $("#complementary_activity").show();
    } else {
        $("#complementary_activity input").val(null).attr('disabled', 'disabled');
        $("#complementary_activity").hide();
    }

    //aee
    if (type == 5) {
        $('#aee input').removeAttr('disabled');
        $("#aee").show();
    } else {
        $('#aee input').attr('disabled', 'disabled');
        $("#aee").hide();
    }

    //M & SvsM
    if (type == 4 || type == 5) {
        $("#modality input").val(null).attr('disabled', 'disabled').trigger('change');
        $("#stage_vs_modality input").val(null).attr('disabled', 'disabled').trigger('change');
        $("#modality").hide();
        $("#stage_vs_modality").hide();
    } else {
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
function updateTime(data) {
    data = jQuery.parseJSON(data);
    $("#Classroom_initial_time").val(data.first !== null ? data.first.substring(0, 5) : $("#Classroom_initial_time").val());
    $("#Classroom_final_time").val(data.last !== null ? data.last.substring(0, 5) : $("#Classroom_final_time").val());
}


// instructor.on('change', atualizarListadeDisciplinas);
// uInstructor.on('change', atualizarListadeDisciplinas);

$(document).on('click', '.deleteTeachingData', removeTeachingData);
$("#addTeachingData").on('click', addTeachingData);

$(document).on("change", ".js-assistance-types-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "Classroom_aee") {
        $("#Classroom_aee").prop("checked", false);
        if ($(this).attr("id") === "Classroom_complementary_activity" && $(this).is(":checked")) {
            $("#complementary_activity").show();
        } else {
            $("#Classroom_complementary_activity_type_1").val(null).trigger("change.select2");
            $("#complementary_activity").hide();
        }
    } else {
        $(".js-assistance-types-container input[type=checkbox]").not("#Classroom_aee").prop("checked", false);
    }
});

$(document).on("change", "#Classroom_pedagogical_mediation_type", function () {
    if ($(this).val() === "1" || $(this).val() === "2") {
        $("#diff_location_container").show();
    } else {
        $("#Classroom_diff_location").val("").trigger("change.select2");
        $("#diff_location_container").hide();
    }
});

$(document).on("change", "#Classroom_edcenso_stage_vs_modality_fk", function () {
    if ($(this).val() !== '' ) {
        $.ajax({
            url: "?r=classroom/updateDisciplinesAndCalendars",
            type: "POST",
            data: {
                id: $("#Classroom_edcenso_stage_vs_modality_fk").val(),
            },
            beforeSend: function () {
                $("#Classroom_edcenso_stage_vs_modality_fk").attr("disabled", "disabled");
                $("#tab-instructor").css("pointer-events", "none");
                $(".loading-disciplines").css("display", "inline-block");
            },
        }).success(function (data) {
            data = JSON.parse(data);
            var html = "";
            if (data.disciplines.length) {
                $(".no-curricular-matrix-error").hide();
                var disciplines = [];
                $.each(data.disciplines, function () {
                    html += "<option value='" + this.id + "'>" + this.name + "</option>";
                    disciplines.push(this.id);
                });
                $("#Disciplines").html(html);
                $(".li-discipline").each(function () {
                    if (!disciplines.includes($(this).attr("discipline"))) {
                        $(this).remove();
                    }
                });
            } else {
                $(".no-curricular-matrix-error").show();
                $("#Disciplines").html("");
                $(".li-discipline").remove();
                $("#DisciplinesWithoutInstructors").html("");
            }

            if (data.calendars.length) {
                html = "";
                html += "<option value=''>Selecione um Calendário</option>";
                $.each(data.calendars, function () {
                    html += "<option value='" + this.id + "'>" + this.title + "</option>";
                });
                $("#Calendars").html(html);
                if ($("#Calendars").attr("selectedOption") === "") {
                    $("#Calendars option:first").attr("selected", "selected").trigger("change.select2");
                } else {
                    $("#Calendars option[value=" + $("#Calendars").attr("selectedOption") + "]").attr("selected", "selected").trigger("change.select2");
                }
            } else {
                $("#Calendars").html("");
            }
        }).complete(function () {
            if (!$("#Classroom_edcenso_stage_vs_modality_fk").hasClass("disabled-field")) {
                $("#Classroom_edcenso_stage_vs_modality_fk").removeAttr("disabled");
            }
            $("#tab-instructor").css("pointer-events", "auto");
            $(".loading-disciplines").hide();
        });
    }
});

$("#Classroom_edcenso_stage_vs_modality_fk").trigger("change");

$("#js-t-sortable").on("sortupdate", function (event, ui) {
    newOrderArray = $(this).sortable("toArray");
    $.ajax({
        url: `${window.location.host}?r=classroom/changeenrollments`,
        type: "POST",
        data: {
            list: newOrderArray
        },
        beforeSend: function () {
            $("#js-t-sortable").sortable("destroy");
            $("#daily").css("opacity", 0.5);
        },
    }).success(function (response) {

        const result = JSON.parse(DOMPurify.sanitize(response));
        const list = []
        result.forEach(element => {
            const li = document.createElement('li');
            li.id = element.id;
            li.className = 'ui-state-default';

            const span1 = document.createElement('span');
            span1.className = 't-icon-slip';

            const span2 = document.createElement('span');
            span2.textContent = element.daily_order + ' ' + element.name;

            li.appendChild(span1);
            li.appendChild(span2);

            list.push(li);
        });

        $("#js-t-sortable").html(list);
        $("#daily").css("opacity", 1);
        $("#js-t-sortable").sortable();
    })
});

$(document).on("click", ".sync-enrollments", function (e) {
    var button = this;
    e.preventDefault();
    $.ajax({
        url: "?r=classroom/syncUnsyncedStudents",
        type: "POST",
        data: {
            classroomId: classroomId
        },
        beforeSend: function () {
            $(button).css("pointer-events", "none");
            $(".loading-sync").show();
        },
    }).success(function (data) {
        data = JSON.parse(data);
        var errors = "";
        $.each(data, function () {
            if (this.valid) {
                $("td[enrollmentid=" + this.enrollmentId + "]").closest("tr").find(".sync-column").html('<img src="' + baseURL + '/img/SyncTrue.png" style="width: 21px;" alt="synced">');
            } else {
                errors += "<b>" + this.studentName + "</b>";
                if (this.identificationMessage != null) {
                    errors += "<br>Ficha do Aluno: " + this.identificationMessage;
                }
                if (this.enrollmentMessage != null) {
                    errors += "<br>Matrícula: " + this.enrollmentMessage;
                }
                errors += "<br><br>";
            }
        });
        if (errors === "") {
            $(".classroom-alert").removeClass("alert-error").addClass("alert-success").text("Matrículas sincronizadas com sucesso!").show();
        } else {
            $(".classroom-alert").addClass("alert-error").removeClass("alert-success").html("As seguintes matrículas não foram sincronizadas:<br><br>" + errors).show();
        }
        $(button).css("pointer-events", "auto");
        $(".loading-sync").hide();
    })
});
